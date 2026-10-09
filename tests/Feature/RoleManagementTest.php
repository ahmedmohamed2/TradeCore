<?php

use App\Enums\Permission;
use App\Models\Permission as PermissionModel;
use App\Models\Role;
use App\Models\User;
use App\Support\RoleName;
use Database\Seeders\RoleAndPermissionSeeder;

test('guests are redirected from role management', function (string $method, string $route) {
    $this->{$method}(route($route))->assertRedirect(route('login'));
})->with([
    'index' => ['get', 'roles.index'],
    'create' => ['get', 'roles.create'],
    'store' => ['post', 'roles.store'],
]);

test('users without permission cannot view roles', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('roles.index'))
        ->assertForbidden();
});

test('the roles index lists roles after sanctum authentication', function () {
    $actor = userWithPermissions(Permission::ViewRoles);
    Role::query()->create([
        'name' => 'Accountant',
        'guard_name' => RoleName::Guard,
    ]);

    $this->actingAs($actor)
        ->get(route('roles.index'))
        ->assertOk()
        ->assertSeeText('Accountant')
        ->assertSeeText(RoleName::label(RoleName::SuperAdmin));
});

test('a role can be created with permissions', function () {
    $actor = userWithPermissions(Permission::CreateRoles, Permission::ViewRoles);

    $this->actingAs($actor)
        ->post(route('roles.store'), [
            'name' => 'Accountant',
            'permissions' => [Permission::ViewSystemSettings->value],
        ])
        ->assertRedirect(route('roles.index'))
        ->assertSessionHas('status');

    $role = Role::findByName('Accountant', RoleName::Guard);

    expect($role->hasPermissionTo(Permission::ViewSystemSettings->value))->toBeTrue()
        ->and($role->hasPermissionTo(Permission::UpdateSystemSettings->value))->toBeFalse();
});

test('the super admin role name is reserved', function () {
    $actor = userWithPermissions(Permission::CreateRoles);

    $this->actingAs($actor)
        ->from(route('roles.create'))
        ->post(route('roles.store'), [
            'name' => 'Super-Admin',
            'permissions' => [],
        ])
        ->assertRedirect(route('roles.create'))
        ->assertSessionHasErrors('name');
});

test('role permissions can be replaced', function () {
    $actor = userWithPermissions(Permission::UpdateRoles, Permission::ViewRoles);
    $role = Role::query()->create([
        'name' => 'Clerk',
        'guard_name' => RoleName::Guard,
    ]);
    $role->givePermissionTo(Permission::ViewUsers->value);

    $this->actingAs($actor)
        ->put(route('roles.update', $role), [
            'name' => 'Senior Clerk',
            'permissions' => [Permission::ViewRoles->value],
        ])
        ->assertRedirect(route('roles.index'));

    $role->refresh();

    expect($role->name)->toBe('Senior Clerk')
        ->and($role->hasPermissionTo(Permission::ViewRoles->value))->toBeTrue()
        ->and($role->hasPermissionTo(Permission::ViewUsers->value))->toBeFalse();
});

test('the super admin role cannot be edited or deleted', function () {
    $actor = User::factory()->superAdmin()->create();
    $role = Role::findByName(RoleName::SuperAdmin, RoleName::Guard);

    $this->actingAs($actor)
        ->get(route('roles.edit', $role))
        ->assertForbidden();

    $this->actingAs($actor)
        ->put(route('roles.update', $role), [
            'name' => 'Renamed',
            'permissions' => [],
        ])
        ->assertForbidden();

    $this->actingAs($actor)
        ->from(route('roles.index'))
        ->delete(route('roles.destroy', $role))
        ->assertRedirect(route('roles.index'))
        ->assertSessionHas('error');

    expect(Role::findByName(RoleName::SuperAdmin, RoleName::Guard)->name)->toBe(RoleName::SuperAdmin);
});

test('a role assigned to a user cannot be deleted', function () {
    $actor = userWithPermissions(Permission::DeleteRoles, Permission::ViewRoles);
    $role = Role::query()->create([
        'name' => 'Clerk',
        'guard_name' => RoleName::Guard,
    ]);
    User::factory()->create()->assignRole($role);

    $this->actingAs($actor)
        ->from(route('roles.index'))
        ->delete(route('roles.destroy', $role))
        ->assertRedirect(route('roles.index'))
        ->assertSessionHas('error');

    $this->assertModelExists($role);
});

test('an unused role can be deleted', function () {
    $actor = userWithPermissions(Permission::DeleteRoles, Permission::ViewRoles);
    $role = Role::query()->create([
        'name' => 'Temporary',
        'guard_name' => RoleName::Guard,
    ]);

    $this->actingAs($actor)
        ->delete(route('roles.destroy', $role))
        ->assertRedirect(route('roles.index'))
        ->assertSessionHas('status');

    $this->assertModelMissing($role);
});

test('permissions granted through a role allow access', function () {
    $role = Role::query()->create([
        'name' => 'Settings clerk',
        'guard_name' => RoleName::Guard,
    ]);
    $role->givePermissionTo(Permission::ViewSystemSettings->value);
    $user = User::factory()->create();
    $user->assignRole($role);

    $this->actingAs($user)
        ->get(route('system-settings.index'))
        ->assertOk();
});

test('the access seeder creates every permission and the super admin role', function () {
    $this->seed(RoleAndPermissionSeeder::class);

    expect(PermissionModel::query()->pluck('name')->all())
        ->toContain(...Permission::values());

    expect(Role::findByName(RoleName::SuperAdmin, RoleName::Guard)->name)->toBe(RoleName::SuperAdmin);
});
