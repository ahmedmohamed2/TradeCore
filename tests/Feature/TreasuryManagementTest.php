<?php

use App\Enums\Permission;
use App\Models\Treasury;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;

test('guests are redirected from treasury management', function (string $method, string $route) {
    $this->{$method}(route($route))->assertRedirect(route('login'));
})->with([
    'index' => ['get', 'treasuries.index'],
    'create' => ['get', 'treasuries.create'],
    'store' => ['post', 'treasuries.store'],
]);

test('guests cannot edit or delete a treasury', function () {
    $treasury = Treasury::factory()->create();

    $this->get(route('treasuries.edit', $treasury))->assertRedirect(route('login'));
    $this->put(route('treasuries.update', $treasury), validTreasuryPayload())->assertRedirect(route('login'));
    $this->delete(route('treasuries.destroy', $treasury))->assertRedirect(route('login'));
});

test('users without permission cannot view treasuries', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('treasuries.index'))
        ->assertForbidden();
});

test('users with permission can view and search treasuries', function () {
    $actor = userWithPermissions(Permission::ViewTreasuries);
    Treasury::factory()->create(['code' => 'MAIN', 'name' => 'Main cash']);
    Treasury::factory()->create(['code' => 'SHOP', 'name' => 'Shop cash']);

    $this->actingAs($actor)
        ->get(route('treasuries.index', ['search' => 'Main']))
        ->assertOk()
        ->assertSeeText('MAIN')
        ->assertSeeText('Main cash')
        ->assertDontSeeText('Shop cash');
});

test('a percent sign in search does not match every treasury', function () {
    $actor = userWithPermissions(Permission::ViewTreasuries);
    Treasury::factory()->create(['name' => '100% Ready']);
    Treasury::factory()->create(['name' => 'Plain Cash']);

    $this->actingAs($actor)
        ->get(route('treasuries.index', ['search' => '%']))
        ->assertOk()
        ->assertSeeText('100% Ready')
        ->assertDontSeeText('Plain Cash');
});

test('users with permission can create a treasury', function () {
    $actor = userWithPermissions(Permission::CreateTreasuries, Permission::ViewTreasuries);

    $this->actingAs($actor)
        ->post(route('treasuries.store'), validTreasuryPayload([
            'code' => 'main-01',
            'is_master' => '1',
        ]))
        ->assertRedirect(route('treasuries.index'))
        ->assertSessionHas('status');

    $treasury = Treasury::query()->where('code', 'MAIN-01')->first();

    expect($treasury)->not->toBeNull()
        ->and($treasury->name)->toBe('Main treasury')
        ->and($treasury->is_master)->toBeTrue()
        ->and($treasury->master_marker)->toBe(1)
        ->and($treasury->opening_balance)->toBe('1500.50')
        ->and($treasury->active)->toBeTrue()
        ->and($treasury->last_payment_number)->toBe(0)
        ->and($treasury->last_collection_number)->toBe(0)
        ->and($treasury->created_by)->toBe($actor->id)
        ->and($treasury->updated_by)->toBe($actor->id);
});

test('creating a treasury requires a code, name, and opening balance', function () {
    $actor = userWithPermissions(Permission::CreateTreasuries);

    $this->actingAs($actor)
        ->from(route('treasuries.create'))
        ->post(route('treasuries.store'), validTreasuryPayload([
            'code' => 'bad code',
            'name' => '',
            'opening_balance' => '',
        ]))
        ->assertRedirect(route('treasuries.create'))
        ->assertSessionHasErrors(['code', 'name', 'opening_balance']);
});

test('only one treasury can be the master', function () {
    $actor = userWithPermissions(Permission::CreateTreasuries);
    Treasury::factory()->master()->create();

    $this->actingAs($actor)
        ->from(route('treasuries.create'))
        ->post(route('treasuries.store'), validTreasuryPayload([
            'code' => 'SECOND',
            'name' => 'Second treasury',
            'is_master' => '1',
        ]))
        ->assertRedirect(route('treasuries.create'))
        ->assertSessionHasErrors('is_master');

    expect(Treasury::query()->where('code', 'SECOND')->exists())->toBeFalse();
});

test('the database rejects a second master treasury', function () {
    Treasury::factory()->master()->create();

    expect(fn () => Treasury::factory()->master()->create())
        ->toThrow(UniqueConstraintViolationException::class);
});

test('users with permission can update a treasury without changing voucher numbers', function () {
    $actor = userWithPermissions(Permission::UpdateTreasuries, Permission::ViewTreasuries);
    $treasury = Treasury::factory()->create([
        'code' => 'OLD',
        'last_payment_number' => 4,
        'last_collection_number' => 9,
        'created_by' => $actor->id,
    ]);

    $this->actingAs($actor)
        ->put(route('treasuries.update', $treasury), validTreasuryPayload([
            'code' => 'new-code',
            'name' => 'Updated treasury',
            'opening_balance' => '20.00',
            'active' => '0',
            'last_payment_number' => 100,
            'last_collection_number' => 100,
        ]))
        ->assertRedirect(route('treasuries.index'))
        ->assertSessionHas('status');

    $treasury->refresh();

    expect($treasury->code)->toBe('NEW-CODE')
        ->and($treasury->name)->toBe('Updated treasury')
        ->and($treasury->opening_balance)->toBe('20.00')
        ->and($treasury->active)->toBeFalse()
        ->and($treasury->last_payment_number)->toBe(4)
        ->and($treasury->last_collection_number)->toBe(9)
        ->and($treasury->created_by)->toBe($actor->id)
        ->and($treasury->updated_by)->toBe($actor->id);
});

test('the current master treasury can stay the master', function () {
    $actor = userWithPermissions(Permission::UpdateTreasuries);
    $treasury = Treasury::factory()->master()->create(['name' => 'Main']);

    $this->actingAs($actor)
        ->put(route('treasuries.update', $treasury), validTreasuryPayload([
            'code' => $treasury->code,
            'name' => 'Main cash',
            'is_master' => '1',
        ]))
        ->assertRedirect(route('treasuries.index'));

    expect($treasury->refresh()->is_master)->toBeTrue()
        ->and($treasury->name)->toBe('Main cash');
});

test('a treasury cannot become master while another master exists', function () {
    $actor = userWithPermissions(Permission::UpdateTreasuries);
    Treasury::factory()->master()->create();
    $treasury = Treasury::factory()->create();

    $this->actingAs($actor)
        ->from(route('treasuries.edit', $treasury))
        ->put(route('treasuries.update', $treasury), validTreasuryPayload([
            'code' => $treasury->code,
            'name' => $treasury->name,
            'is_master' => '1',
        ]))
        ->assertRedirect(route('treasuries.edit', $treasury))
        ->assertSessionHasErrors('is_master');

    expect($treasury->refresh()->is_master)->toBeFalse();
});

test('users with permission can delete a treasury', function () {
    $actor = userWithPermissions(Permission::DeleteTreasuries, Permission::ViewTreasuries);
    $treasury = Treasury::factory()->create();

    $this->actingAs($actor)
        ->delete(route('treasuries.destroy', $treasury))
        ->assertRedirect(route('treasuries.index'))
        ->assertSessionHas('status');

    $this->assertModelMissing($treasury);
});

test('super admins can open treasuries without a direct permission', function () {
    $actor = User::factory()->superAdmin()->create();

    $this->actingAs($actor)
        ->get(route('treasuries.index'))
        ->assertOk()
        ->assertSeeText(__('menu.treasuries'));
});

test('the sidebar shows treasuries only when the user can manage them', function () {
    $actor = userWithPermissions(Permission::ViewTreasuries);

    $this->actingAs($actor)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeText(__('menu.accounting'))
        ->assertSeeText(__('menu.treasuries'))
        ->assertDontSeeText(__('menu.users'));
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function validTreasuryPayload(array $overrides = []): array
{
    return array_merge([
        'code' => 'MAIN',
        'name' => 'Main treasury',
        'is_master' => '0',
        'opening_balance' => '1500.50',
        'notes' => 'Front desk cash',
        'active' => '1',
    ], $overrides);
}
