<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentMethodPolicy
{
    /**
     * Determine whether the user can manage payment methods.
     */
    public function managePaymentMethods(User $user): bool
    {
        return $user->isAdmin();
    }
}
