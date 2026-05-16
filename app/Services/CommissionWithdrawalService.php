<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\SaleTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CommissionWithdrawalService
{
    private static ?bool $supportsWithdrawalTransactionId = null;

    private static function supportsWithdrawalTransactionId(): bool
    {
        if (self::$supportsWithdrawalTransactionId !== null) {
            return self::$supportsWithdrawalTransactionId;
        }

        try {
            self::$supportsWithdrawalTransactionId = Schema::hasColumn('commissions', 'withdrawal_transaction_id');
        } catch (\Throwable) {
            self::$supportsWithdrawalTransactionId = false;
        }

        return self::$supportsWithdrawalTransactionId;
    }

    public function reserveForWithdrawal(SaleTransaction $transaction, float $amountToReserve): void
    {
        if (($transaction->transaction_type ?? null) !== 'withdrawal') {
            return;
        }

        $remainingAmount = (float) $amountToReserve;
        if ($remainingAmount <= 0) {
            return;
        }

        $commissions = Commission::query()
            ->where('user_id', $transaction->user_id)
            ->where('withdrawn', false)
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->get();

        foreach ($commissions as $commission) {
            if ($remainingAmount <= 0) {
                break;
            }

            $commissionAmount = (float) $commission->amount;

            if ($commissionAmount <= $remainingAmount) {
                $updates = [
                    'withdrawn' => true,
                ];

                if (self::supportsWithdrawalTransactionId()) {
                    $updates['withdrawal_transaction_id'] = $transaction->id;
                }

                $commission->update($updates);
                $remainingAmount -= $commissionAmount;
                continue;
            }

            // Partially reserve this commission by splitting it.
            $newCommissionPayload = [
                'user_id' => $commission->user_id,
                'sale_transaction_id' => $commission->sale_transaction_id,
                'amount' => $commissionAmount - $remainingAmount,
                'period_date' => $commission->period_date,
                'period_type' => $commission->period_type,
                'withdrawn' => false,
            ];

            if (self::supportsWithdrawalTransactionId()) {
                $newCommissionPayload['withdrawal_transaction_id'] = null;
            }

            Commission::create($newCommissionPayload);

            $updates = [
                'amount' => $remainingAmount,
                'withdrawn' => true,
            ];

            if (self::supportsWithdrawalTransactionId()) {
                $updates['withdrawal_transaction_id'] = $transaction->id;
            }

            $commission->update($updates);

            $remainingAmount = 0;
        }

        if ($remainingAmount > 0) {
            throw new \RuntimeException('Saldo komisi tidak mencukupi untuk penarikan.');
        }
    }

    public function restoreForWithdrawal(SaleTransaction $transaction): void
    {
        if (($transaction->transaction_type ?? null) !== 'withdrawal') {
            return;
        }

        if (self::supportsWithdrawalTransactionId()) {
            $commissions = Commission::query()
                ->where('user_id', $transaction->user_id)
                ->where('withdrawn', true)
                ->where('withdrawal_transaction_id', $transaction->id)
                ->orderBy('updated_at', 'desc')
                ->lockForUpdate()
                ->get();

            // If we have commissions explicitly tagged to this withdrawal, restore all of them.
            // This avoids incorrect refunds when the user has multiple withdrawals.
            if ($commissions->isNotEmpty()) {
                foreach ($commissions as $commission) {
                    $commission->update([
                        'withdrawn' => false,
                        'withdrawal_transaction_id' => null,
                    ]);
                }

                return;
            }
        }

        $amountToRestore = (float) ($transaction->amount ?? 0);
        if ($amountToRestore <= 0) {
            return;
        }

        // Backward-compatibility:
        // - DB not migrated yet (no withdrawal_transaction_id column), or
        // - legacy withdrawals that didn't tag commissions.
        // Only attempt the legacy behavior when there are no other non-final withdrawals,
        // otherwise we can't safely know which commissions to restore.
        $nonFinalWithdrawals = SaleTransaction::query()
            ->where('user_id', $transaction->user_id)
            ->where('transaction_type', 'withdrawal')
            ->whereIn('status', ['pending', 'process'])
            ->count();

        if ($nonFinalWithdrawals > 1) {
            Log::warning('Withdrawal refund skipped due to ambiguous legacy commissions (multiple pending/process withdrawals)', [
                'withdrawal_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'amount' => (float) $transaction->amount,
            ]);

            return;
        }

        $legacyCommissionsQuery = Commission::query()
            ->where('user_id', $transaction->user_id)
            ->where('withdrawn', true);

        if (self::supportsWithdrawalTransactionId()) {
            $legacyCommissionsQuery->whereNull('withdrawal_transaction_id');
        }

        $commissions = $legacyCommissionsQuery
            ->orderBy('updated_at', 'desc')
            ->lockForUpdate()
            ->get();

        foreach ($commissions as $commission) {
            if ($amountToRestore <= 0) {
                break;
            }

            $commissionAmount = (float) $commission->amount;

            if ($commissionAmount <= $amountToRestore) {
                $updates = [
                    'withdrawn' => false,
                ];
                if (self::supportsWithdrawalTransactionId()) {
                    $updates['withdrawal_transaction_id'] = null;
                }

                $commission->update($updates);
                $amountToRestore -= $commissionAmount;
                continue;
            }

            $refundAmount = $amountToRestore;
            $remainingWithdrawn = $commissionAmount - $refundAmount;

            $refundPayload = [
                'user_id' => $commission->user_id,
                'sale_transaction_id' => $commission->sale_transaction_id,
                'amount' => $refundAmount,
                'period_date' => $commission->period_date,
                'period_type' => $commission->period_type,
                'withdrawn' => false,
            ];

            if (self::supportsWithdrawalTransactionId()) {
                $refundPayload['withdrawal_transaction_id'] = null;
            }

            Commission::create($refundPayload);

            $commission->update([
                'amount' => $remainingWithdrawn,
            ]);

            $amountToRestore = 0;
        }

        if ($amountToRestore > 0) {
            Log::warning('Withdrawal refund could not fully restore amount (insufficient tagged withdrawn commissions)', [
                'withdrawal_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'remaining' => $amountToRestore,
                'amount' => (float) $transaction->amount,
            ]);
        }
    }
}
