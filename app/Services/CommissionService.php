<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\SaleTransaction;
use App\Models\Service;
use Carbon\Carbon;

class CommissionService
{
    /**
     * Calculate and create/update commission record for a sale transaction
     *
     * @param SaleTransaction $saleTransaction
     * @return Commission|null
     */
    public function calculateAndCreateCommission(SaleTransaction $saleTransaction): ?Commission
    {
        // Only create/update commission if the transaction is successful
        if ($saleTransaction->status !== 'success') {
            return null;
        }

        if ($saleTransaction->transaction_type === 'withdrawal') {
            return null;
        }

        // Calculate commission amount (always recalculate for consistency)
        // Special case: Hunter host submissions use the service minimum nominal as commission amount.
        if ($saleTransaction->transaction_type === 'host_submit') {
            $service = $saleTransaction->hostSubmission()
                ->with('service:id,minimum_nominal,name,category')
                ->first()
                ?->service;

            if (! $service && ! empty($saleTransaction->service_name)) {
                $service = Service::query()
                    ->where('name', (string) $saleTransaction->service_name)
                    // Prefer talent_hunter when duplicates exist, otherwise take the first match.
                    ->orderByRaw("CASE WHEN category = 'talent_hunter' THEN 0 ELSE 1 END")
                    ->select('id', 'minimum_nominal', 'name', 'category')
                    ->first();
            }

            if ($service) {
                $commissionAmount = (float) max(0, (int) ($service->minimum_nominal ?? 15000));
            } else {
                $commissionAmount = (float) env('HUNTER_COMMISSION_AMOUNT', 1000);
            }
        } else {
            $commissionAmount = $saleTransaction->amount * ($saleTransaction->commission_rate / 100);
        }

        // Determine period type and date
        $periodDate = Carbon::parse($saleTransaction->completed_at ?? $saleTransaction->updated_at);
        $periodType = 'daily'; // Could be extended to monthly, weekly, etc.

        // Check if commission already exists for this transaction for the user
        $existingCommission = Commission::where('sale_transaction_id', $saleTransaction->id)
            ->where('user_id', $saleTransaction->user_id)
            ->first();

        if ($existingCommission) {
            // Update existing commission record
            $existingCommission->update([
                'amount' => $commissionAmount,
                'period_date' => $periodDate,
            ]);
        } else {
            // Create new commission record
            $commission = Commission::create([
                'user_id' => $saleTransaction->user_id,
                'sale_transaction_id' => $saleTransaction->id,
                'amount' => $commissionAmount,
                'period_date' => $periodDate,
                'period_type' => $periodType,
            ]);
        }

        // New logic for marketing commission
        $user = $saleTransaction->user;
        if ($user && $user->marketing_owner_id) {
            $existingMarketingCommission = Commission::where('sale_transaction_id', $saleTransaction->id)
                ->where('user_id', $user->marketing_owner_id)
                ->first();

            if ($existingMarketingCommission) {
                $existingMarketingCommission->update([
                    'amount' => $commissionAmount,
                    'period_date' => $periodDate,
                ]);
            } else {
                Commission::create([
                    'user_id' => $user->marketing_owner_id,
                    'sale_transaction_id' => $saleTransaction->id,
                    'amount' => $commissionAmount,
                    'period_date' => $periodDate,
                    'period_type' => $periodType,
                ]);
            }
        }

        // Sync commission_amount field on SaleTransaction for consistency
        if ($saleTransaction->commission_amount != $commissionAmount) {
            $saleTransaction->commission_amount = $commissionAmount;
            $saleTransaction->saveQuietly();
        }

        return $existingCommission ?? $commission ?? null;
    }

    /**
     * Calculate commission amount based on transaction amount and rate
     *
     * @param float $amount
     * @param float $rate
     * @return float
     */
    public function calculateCommission(float $amount, float $rate): float
    {
        return $amount * ($rate / 100);
    }

    /**
     * Get total commission for a user in a specific period
     *
     * @param int $userId
     * @param string $periodType
     * @param string|null $periodDate
     * @return float
     */
    public function getTotalCommissionForUser(int $userId, string $periodType = 'daily', string $periodDate = null): float
    {
        $query = Commission::where('user_id', $userId)->where('period_type', $periodType);

        if ($periodDate) {
            $query->whereDate('period_date', $periodDate);
        }

        return $query->sum('amount');
    }
}
