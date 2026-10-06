<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Support\PermissionCatalog;
use App\Support\RoleName;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class RoleController extends Controller implements HasMiddleware
{
    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:'.Permission::ViewRoles->value, only: ['index']),
            new Middleware('permission:'.Permission::CreateRoles->value, only: ['create', 'store']),
            new Middleware('permission:'.Permission::UpdateRoles->value, only: ['edit', 'update']),
            new Middleware('permission:'.Permission::DeleteRoles->value, only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $roles = Role::query()
            ->where('guard_name', RoleName::Guard)
            ->withCount('users')
            ->orderBy('name')
            ->paginate(15);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('roles.create', [
            'permissionGroups' => PermissionCatalog::grouped(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::query()->create([
            'name' => $request->validated('name'),
            'guard_name' => RoleName::Guard,
        ]);

        $role->syncPermissions($request->validated('permissions') ?? []);

        return to_route('roles.index')->with('status', __('roles.created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): View
    {
        abort_if($role->isSuperAdmin(), 403);

        return view('roles.edit', [
            'role' => $role->load('permissions'),
            'permissionGroups' => PermissionCatalog::grouped(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $role->update([
            'name' => $request->validated('name'),
        ]);

        $role->syncPermissions($request->validated('permissions') ?? []);

        return to_route('roles.index')->with('status', __('roles.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->isSuperAdmin()) {
            return back()->with('error', __('roles.cannot_delete_super_admin'));
        }

        if ($role->users()->exists()) {
            return back()->with('error', __('roles.cannot_delete_assigned'));
        }

        $role->delete();

        return to_route('roles.index')->with('status', __('roles.deleted'));
    }
}
