<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    private const ALLOWED_ROLES = ['owner', 'admin', 'customer'];

    private const ALLOWED_SORTS = ['name', 'email', 'created_at'];

    /**
     * Display the owner's user management list.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $search = trim((string) $request->query('search', ''));
        $roleFilter = trim((string) $request->query('role', ''));
        $sort = in_array($request->query('sort'), self::ALLOWED_SORTS, true)
            ? $request->query('sort')
            : 'created_at';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $users = User::with('roles')
            ->when($search !== '', function ($query) use ($search) {
                $pattern = '%'.Str::lower($search).'%';
                $query->where(function ($subQuery) use ($pattern) {
                    $subQuery->whereRaw('LOWER(name) LIKE ?', [$pattern])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$pattern]);
                });
            })
            ->when(in_array($roleFilter, self::ALLOWED_ROLES, true), function ($query) use ($roleFilter) {
                $query->role($roleFilter);
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('owner.users.index', [
            'users' => $users,
            'roles' => self::ALLOWED_ROLES,
            'search' => $search,
            'roleFilter' => $roleFilter,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    /**
     * Store a newly created user account with assigned role.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $validated = $request->validated();

        Role::firstOrCreate([
            'name' => $validated['role'],
            'guard_name' => 'web',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('owner.users.index')
            ->with('success', "Akun {$user->name} berhasil dibuat dengan role {$validated['role']}.");
    }

    /**
     * Update the target user's role (forbidden for owner's own account).
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validated();

        Role::firstOrCreate([
            'name' => $validated['role'],
            'guard_name' => 'web',
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('owner.users.index')
            ->with('success', "Role {$user->name} berhasil diubah menjadi {$validated['role']}.");
    }

    /**
     * Delete the target user account (forbidden for owner's own account).
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $name = $user->name;
        $user->tokens()->delete();
        $user->delete();

        return redirect()
            ->route('owner.users.index')
            ->with('success', "Akun {$name} berhasil dihapus.");
    }

    /**
     * Display read-only list of customers for Admin.
     */
    public function customers(Request $request): View
    {
        Gate::authorize('viewCustomers', User::class);

        $search = trim((string) $request->query('search', ''));
        $sort = in_array($request->query('sort'), self::ALLOWED_SORTS, true)
            ? $request->query('sort')
            : 'created_at';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $customers = User::role('customer')
            ->with('roles')
            ->withCount('orders')
            ->when($search !== '', function ($query) use ($search) {
                $pattern = '%'.Str::lower($search).'%';
                $query->where(function ($subQuery) use ($pattern) {
                    $subQuery->whereRaw('LOWER(name) LIKE ?', [$pattern])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$pattern]);
                });
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', [
            'customers' => $customers,
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }
}
