<?php

namespace App\Policies;

use App\Models\SaleTransaction;
use App\Models\User;

class SaleTransactionPolicy
{
    private function isInMarketingScope(User $marketing, SaleTransaction $saleTransaction): bool
    {
        if ((int) $saleTransaction->user_id === (int) $marketing->id) {
            return true;
        }

        return $saleTransaction->user()
            ->where('marketing_owner_id', $marketing->id)
            ->exists();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SaleTransaction $saleTransaction): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isMarketing()) {
            return $this->isInMarketingScope($user, $saleTransaction);
        }

        return (int) $user->id === (int) $saleTransaction->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SaleTransaction $saleTransaction): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SaleTransaction $saleTransaction): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SaleTransaction $saleTransaction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SaleTransaction $saleTransaction): bool
    {
        return false;
    }
}
