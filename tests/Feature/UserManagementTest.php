<?php

use App\Enums\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\RoleName;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;

test('guests are redirected from user management', function (string $method, string $route) {
    $this->{$method}(route($route))->assertRedirect(route('login'));
})->with([
    'index' => ['get', 'users.index'],
    'create' => ['get', 'users.create'],
    'store' => ['post', 'users.store'],
]);

test('guests cannot edit or delete a user', function () {
    $user = User::factory()->create();

    $this->get(route('users.edit', $user))->assertRedirect(route('login'));
    $this->put(route('users.update', $user), validUserPayload())->assertRedirect(route('login'));
    $this->delete(route('users.destroy', $user))->assertRedirect(route('login'));
});

test('users without permission cannot view the directory', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('users.index'))
        ->assertForbidden();
});

test('users with permission can view and search the directory', function () {
    $actor = userWithPermissions(Permission::ViewUsers);
    $match = User::factory()->create(['name' => 'Nadia Search', 'email' => 'nadia@example.com']);
    User::factory()->create(['name' => 'Other Person', 'email' => 'other@example.com']);

    $this->actingAs($actor)
        ->get(route('users.index', ['search' => 'Nadia']))
        ->assertOk()
        ->assertSeeText('Nadia Search')
        ->assertSeeText('nadia@example.com')
        ->assertDontSeeText('Other Person');

    expect($match->exists)->toBeTrue();
});

test('a percent sign in search does not match every user', function () {
    $actor = userWithPermissions(Permission::ViewUsers);
    User::factory()->create(['name' => '100% Ready']);
    User::factory()->create(['name' => 'Plain Name']);

    $this->actingAs($actor)
        ->get(route('users.index', ['search' => '%']))
        ->assertOk()
        ->assertSeeText('100% Ready')
        ->assertDontSeeText('Plain Name');
});

test('users with permission can create a user and assign a role', function () {
    $actor = userWithPermissions(Permission::CreateUsers, Permission::ViewUsers);
    $role = Role::query()->create([
        'name' => 'Accountant',
        'guard_name' => RoleName::Guard,
    ]);
    $role->givePermissionTo(Permission::ViewSystemSettings->value);

    $this->actingAs($actor)
        ->post(route('users.store'), validUserPayload([
            'roles' => ['Accountant'],
        ]))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('status');

    $created = User::query()->where('email', 'mona@example.com')->first();

    expect($created)->not->toBeNull()
        ->and(Hash::check('password-1', $created->password))->toBeTrue()
        ->and($created->hasRole('Accountant'))->toBeTrue()
        ->and($created->can(Permission::ViewSystemSettings->value))->toBeTrue();
});

test('creating a user requires a name, email, and password', function () {
    $actor = userWithPermissions(Permission::CreateUsers);

    $this->actingAs($actor)
        ->from(route('users.create'))
        ->post(route('users.store'), validUserPayload([
            'name' => '',
            'email' => 'not-an-email',
            'password' => '',
            'password_confirmation' => '',
        ]))
        ->assertRedirect(route('users.create'))
        ->assertSessionHasErrors(['name', 'email', 'password']);
});

test('only a super admin can assign the super admin role', function () {
    $actor = userWithPermissions(Permission::CreateUsers);
    Role::findByName(RoleName::SuperAdmin, RoleName::Guard);

    $this->actingAs($actor)
        ->from(route('users.create'))
        ->post(route('users.store'), validUserPayload([
            'roles' => [RoleName::SuperAdmin],
        ]))
        ->assertRedirect(route('users.create'))
        ->assertSessionHasErrors('roles');

    expect(User::query()->where('email', 'mona@example.com')->exists())->toBeFalse();
});

test('super admins can assign the super admin role', function () {
    $actor = User::factory()->superAdmin()->create();

    $this->actingAs($actor)
        ->post(route('users.store'), validUserPayload([
            'roles' => [RoleName::SuperAdmin],
        ]))
        ->assertRedirect(route('users.index'));

    expect(User::query()->where('email', 'mona@example.com')->first()?->hasRole(RoleName::SuperAdmin))->toBeTrue();
});

test('users can be updated without changing the password', function () {
    $actor = userWithPermissions(Permission::UpdateUsers, Permission::ViewUsers);
    $subject = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);
    $password = $subject->password;

    $this->actingAs($actor)
        ->put(route('users.update', $subject), validUserPayload([
            'name' => 'New Name',
            'email' => 'new@example.com',
            'locale' => 'en',
            'password' => '',
            'password_confirmation' => '',
            'roles' => [],
        ]))
        ->assertRedirect(route('users.index'));

    $subject->refresh();

    expect($subject->name)->toBe('New Name')
        ->and($subject->email)->toBe('new@example.com')
        ->and($subject->locale)->toBe('en')
        ->and($subject->password)->toBe($password);
});

test('a user password can be replaced', function () {
    $actor = userWithPermissions(Permission::UpdateUsers, Permission::ViewUsers);
    $subject = User::factory()->create();

    $this->actingAs($actor)
        ->put(route('users.update', $subject), validUserPayload([
            'email' => $subject->email,
            'password' => 'replacement-1',
            'password_confirmation' => 'replacement-1',
        ]))
        ->assertRedirect(route('users.index'));

    expect(Hash::check('replacement-1', $subject->fresh()->password))->toBeTrue();
});

test('a non super admin cannot edit a super admin', function () {
    $actor = userWithPermissions(Permission::UpdateUsers);
    $subject = User::factory()->superAdmin()->create(['name' => 'Protected Admin']);

    $this->actingAs($actor)
        ->get(route('users.edit', $subject))
        ->assertForbidden();

    $this->actingAs($actor)
        ->put(route('users.update', $subject), validUserPayload([
            'name' => 'Hijacked',
            'email' => $subject->email,
        ]))
        ->assertForbidden();

    expect($subject->fresh()->name)->toBe('Protected Admin');
});

test('the last super admin role cannot be removed', function () {
    $actor = User::factory()->superAdmin()->create(['email' => 'only-admin@example.com']);

    $this->actingAs($actor)
        ->from(route('users.edit', $actor))
        ->put(route('users.update', $actor), validUserPayload([
            'name' => $actor->name,
            'email' => $actor->email,
            'roles' => [],
        ]))
        ->assertRedirect(route('users.edit', $actor))
        ->assertSessionHasErrors('roles');

    expect($actor->fresh()->hasRole(RoleName::SuperAdmin))->toBeTrue();
});

test('a user cannot delete their own account from the directory', function () {
    $actor = userWithPermissions(Permission::DeleteUsers, Permission::ViewUsers);

    $this->actingAs($actor)
        ->from(route('users.index'))
        ->delete(route('users.destroy', $actor))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('error', __('users.cannot_delete_self'));

    $this->assertModelExists($actor);
});

test('the last super admin cannot be deleted', function () {
    $actor = User::factory()->superAdmin()->create();

    $this->actingAs($actor)
        ->from(route('users.index'))
        ->delete(route('users.destroy', $actor))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('error', __('users.cannot_delete_last_super_admin'));

    $this->assertModelExists($actor);
});

test('users with permission can delete another user', function () {
    $actor = userWithPermissions(Permission::DeleteUsers, Permission::ViewUsers);
    $subject = User::factory()->create();

    $this->actingAs($actor)
        ->delete(route('users.destroy', $subject))
        ->assertRedirect(route('users.index'))
        ->assertSessionHas('status');

    $this->assertModelMissing($subject);
});

test('super admins can open user management without a direct permission', function () {
    $actor = User::factory()->superAdmin()->create();

    $this->actingAs($actor)
        ->get(route('users.index'))
        ->assertOk()
        ->assertSeeText(__('menu.users'));
});

test('the sidebar shows only the access links a user is allowed to open', function () {
    $actor = userWithPermissions(Permission::ViewUsers);

    $this->actingAs($actor)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeText(__('menu.users'))
        ->assertDontSeeText(__('menu.roles'))
        ->assertDontSeeText(__('menu.system_settings'));
});

test('the user seeder creates a super admin', function () {
    $this->seed(UserSeeder::class);

    $admin = User::query()->where('email', 'super_admin@app.com')->first();

    expect($admin)->not->toBeNull()
        ->and($admin->hasRole(RoleName::SuperAdmin))->toBeTrue();
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function validUserPayload(array $overrides = []): array
{
    return [
        'name' => 'Mona Adel',
        'email' => 'mona@example.com',
        'password' => 'password-1',
        'password_confirmation' => 'password-1',
        'locale' => 'ar',
        'roles' => [],
        ...$overrides,
    ];
}
