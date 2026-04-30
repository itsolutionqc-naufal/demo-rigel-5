<?php

namespace App\Http\Controllers;

use App\Models\SaleTransaction;
use App\Models\Setting;
use App\Models\User;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function isMarketingUsersRoute(Request $request): bool
    {
        return $request->routeIs('marketing.users.*');
    }

    private function abortIfMarketingCannotAccessUser(Request $request, User $user): void
    {
        $actor = $request->user();

        if (! $actor || ! $actor->isMarketing()) {
            return;
        }

        if (! $this->isMarketingUsersRoute($request)) {
            return;
        }

        if ($user->role !== User::ROLE_USER || ! $user->isManagedBy($actor)) {
            abort(404);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        $query->with(['marketingOwner' => function ($q) {
            $q->select('id', 'name', 'username', 'email');
        }]);

        if ($this->isMarketingUsersRoute($request)) {
            $query->where('role', User::ROLE_USER)
                ->where('marketing_owner_id', $request->user()->id);
        }

        // Apply date filters
        if ($request->filled('start_date')) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', (string) $request->start_date)->startOfDay();
                $query->where('created_at', '>=', $start);
            } catch (\Throwable $e) {
                // ignore invalid date
            }
        }

        if ($request->filled('end_date')) {
            try {
                $end = Carbon::createFromFormat('Y-m-d', (string) $request->end_date)->endOfDay();
                $query->where('created_at', '<=', $end);
            } catch (\Throwable $e) {
                // ignore invalid date
            }
        }

        // Apply role filter
        if ($request->filled('role') && ! $this->isMarketingUsersRoute($request)) {
            if ($request->role === 'user_marketing') {
                $query->where('role', User::ROLE_USER)
                    ->whereNotNull('marketing_owner_id');
            } else {
                $query->where('role', $request->role);
            }
        }

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        $users = $query->paginate(10)->appends($request->query());

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $marketingOwners = collect();
        if ($request->user() && $request->user()->isAdmin()) {
            $marketingOwners = User::query()
                ->where('role', User::ROLE_MARKETING)
                ->orderBy('name')
                ->get(['id', 'name', 'email']);
        }

        return view('users.create', compact('marketingOwners'));
    }

    private function usersIndexRouteName(Request $request): string
    {
        $actor = $request->user();

        if ($actor && $actor->isMarketing()) {
            return 'marketing.users.index';
        }

        return 'users.index';
    }

    private function allowedRoleValuesForCurrentUser(Request $request): array
    {
        $actor = $request->user();

        if ($actor && $actor->isAdmin()) {
            return [User::ROLE_USER, User::ROLE_ADMIN, User::ROLE_MARKETING];
        }

        return [User::ROLE_USER];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $actor = $request->user();
        $allowedRoles = $this->allowedRoleValuesForCurrentUser($request);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
        ];

        if (! $this->isMarketingUsersRoute($request)) {
            $rules['role'] = 'required|in:'.implode(',', $allowedRoles);

            if ($actor && $actor->isAdmin()) {
                $rules['marketing_owner_id'] = [
                    'nullable',
                    Rule::exists('users', 'id')->where('role', User::ROLE_MARKETING),
                ];
            }
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $this->isMarketingUsersRoute($request) ? User::ROLE_USER : $request->role,
        ];

        if ($this->isMarketingUsersRoute($request)) {
            $userData['marketing_owner_id'] = $request->user()->id;
        } elseif ($actor && $actor->isAdmin()) {
            $userData['marketing_owner_id'] = $userData['role'] === User::ROLE_USER
                ? ($request->input('marketing_owner_id') ?: null)
                : null;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $filename = time().'_user_avatar_'.$request->name.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/images/avatar/users');

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $filename);
            $userData['avatar'] = 'uploads/images/avatar/users/'.$filename;
        }

        $createdUser = User::create($userData);

        if ($actor && $actor->isMarketing() && $this->isMarketingUsersRoute($request)) {
            $defaultBaseAmount = (int) config('marketing.user_create_bonus_amount', 1000);
            $bonusMode = (string) Setting::get('marketing.user_create_bonus_mode', 'nominal');

            $bonusAmount = match ($bonusMode) {
                'default' => $defaultBaseAmount,
                'percent' => (int) round($defaultBaseAmount * (((float) Setting::get('marketing.user_create_bonus_percent', 10)) / 100)),
                default => (int) Setting::get('marketing.user_create_bonus_amount', $defaultBaseAmount),
            };

            if ($bonusAmount > 0) {
                $bonusTransaction = SaleTransaction::create([
                    'user_id' => $actor->id,
                    'amount' => $bonusAmount,
                    'status' => 'success',
                    'transaction_type' => 'user_onboarding_bonus',
                    'description' => "Bonus onboarding: user #{$createdUser->id} ({$createdUser->email})",
                    'commission_rate' => 100,
                    'commission_amount' => $bonusAmount,
                    'completed_at' => now(),
                ]);

                app(CommissionService::class)->calculateAndCreateCommission($bonusTransaction);
            }
        }

        return redirect()->route($this->usersIndexRouteName($request))->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $this->abortIfMarketingCannotAccessUser($request, $user);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user)
    {
        $this->abortIfMarketingCannotAccessUser($request, $user);

        $marketingOwners = collect();
        if ($request->user() && $request->user()->isAdmin()) {
            $marketingOwners = User::query()
                ->where('role', User::ROLE_MARKETING)
                ->orderBy('name')
                ->get(['id', 'name', 'email']);
        }

        return view('users.edit', compact('user', 'marketingOwners'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->abortIfMarketingCannotAccessUser($request, $user);

        $actor = $request->user();
        $allowedRoles = $this->allowedRoleValuesForCurrentUser($request);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
        ];

        if (! $this->isMarketingUsersRoute($request)) {
            $rules['role'] = 'required|in:'.implode(',', $allowedRoles);

            if ($actor && $actor->isAdmin()) {
                $rules['marketing_owner_id'] = [
                    'nullable',
                    Rule::exists('users', 'id')->where('role', User::ROLE_MARKETING),
                ];
            }
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $this->isMarketingUsersRoute($request) ? User::ROLE_USER : $request->role,
        ];

        if ($this->isMarketingUsersRoute($request)) {
            $userData['marketing_owner_id'] = $request->user()->id;
        } elseif ($actor && $actor->isAdmin()) {
            $userData['marketing_owner_id'] = $userData['role'] === User::ROLE_USER
                ? ($request->input('marketing_owner_id') ?: null)
                : null;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $filename = time().'_user_avatar_'.$user->id.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/images/avatar/users');

            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $filename);

            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $userData['avatar'] = 'uploads/images/avatar/users/'.$filename;
        }

        $user->update($userData);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route($this->usersIndexRouteName($request))->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $actor = $request->user();
        if (! $actor || ! $actor->isAdmin()) {
            $message = 'Anda tidak memiliki akses untuk menghapus user.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 403);
            }

            if (! $actor) {
                return redirect()->guest(route('login'));
            }

            return redirect()->route($actor->homeRouteName())->with('error', $message);
        }

        if ((int) $user->id === (int) $actor->id) {
            $message = 'Anda tidak dapat menghapus akun Anda sendiri!';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->route($this->usersIndexRouteName($request))->with('error', $message);
        }

        $user->delete();

        $message = 'User berhasil dihapus!';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->route($this->usersIndexRouteName($request))->with('success', $message);
    }
}
