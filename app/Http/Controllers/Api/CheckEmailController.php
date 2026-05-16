<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CheckEmailController extends Controller
{
    /**
     * Check if email exists (including soft deleted users)
     */
    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        // Check for active user
        $activeUser = User::where('email', $email)->first();
        
        if ($activeUser) {
            return response()->json([
                'exists' => true,
                'deleted' => false,
                'message' => 'Email sudah terdaftar dan aktif',
            ]);
        }

        // Check for soft deleted user
        $deletedUser = User::onlyTrashed()
            ->where('email', $email)
            ->first();

        if ($deletedUser) {
            // Get transaction stats
            $stats = \App\Models\SaleTransaction::where('user_id', $deletedUser->id)
                ->selectRaw('
                    COUNT(*) as total_transactions,
                    SUM(amount) as total_amount,
                    MAX(created_at) as last_transaction
                ')
                ->first();

            return response()->json([
                'exists' => true,
                'deleted' => true,
                'data' => [
                    'name' => $deletedUser->name,
                    'email' => $deletedUser->email,
                    'username' => $deletedUser->username,
                    'role' => $deletedUser->role,
                    'total_transactions' => $stats->total_transactions ?? 0,
                    'total_amount' => $stats->total_amount ?? 0,
                    'total_amount_formatted' => 'Rp ' . number_format($stats->total_amount ?? 0, 0, ',', '.'),
                    'last_transaction' => $stats->last_transaction ? $stats->last_transaction->format('d M Y') : null,
                    'deleted_at' => $deletedUser->deleted_at->format('d M Y H:i'),
                ],
            ]);
        }

        return response()->json([
            'exists' => false,
            'deleted' => false,
            'message' => 'Email tersedia',
        ]);
    }

    /**
     * Restore deleted user account
     */
    public function restore(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'email_confirmation' => 'required|email|same:email',
            'password' => 'required|min:8',
        ]);

        $deletedUser = User::onlyTrashed()
            ->where('email', $request->email)
            ->first();

        if (!$deletedUser) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak ditemukan atau sudah aktif',
            ], 404);
        }

        // Restore user
        $deletedUser->restore();

        // Update password
        $deletedUser->password = bcrypt($request->password);
        $deletedUser->save();

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil di-restore! Silakan login.',
            'data' => [
                'name' => $deletedUser->name,
                'email' => $deletedUser->email,
            ],
        ]);
    }
}
