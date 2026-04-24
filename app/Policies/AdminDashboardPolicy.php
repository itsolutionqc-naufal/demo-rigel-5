<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminDashboardPolicy
{
    /**
     * Determine whether the user can view the admin dashboard.
     */
    public function viewAdminDashboard(User $user): bool
    {
        return $user->isAdmin();
    }
}
