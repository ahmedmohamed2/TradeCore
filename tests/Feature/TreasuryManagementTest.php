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

test('the treasury list shows notes, counters, and who changed the record', function () {
    $actor = userWithPermissions(Permission::ViewTreasuries);
    $creator = User::factory()->create(['name' => 'Lina Cashier']);
    $updater = User::factory()->create(['name' => 'Omar Auditor']);
    $treasury = Treasury::factory()->create([
        'code' => 'FRONT',
        'name' => 'Front desk',
        'opening_balance' => '1500.50',
        'last_collection_number' => 8142,
        'last_payment_number' => 9031,
        'notes' => "Front desk cash\nSecond line",
        'is_master' => false,
        'active' => true,
        'created_by' => $creator->id,
        'updated_by' => $updater->id,
    ]);

    $this->actingAs($actor)
        ->get(route('treasuries.index'))
        ->assertOk()
        ->assertSeeText('FRONT')
        ->assertSeeText('Front desk')
        ->assertSeeText('1,500.50')
        ->assertSeeText(__('treasuries.master'))
        ->assertSeeText('8142')
        ->assertSeeText('9031')
        ->assertSeeText("Front desk cash\nSecond line")
        ->assertSeeText('Lina Cashier')
        ->assertSeeText('Omar Auditor')
        ->assertSeeText($treasury->created_at->translatedFormat('d M Y, h:i A'))
        ->assertSeeText($treasury->updated_at->translatedFormat('d M Y, h:i A'))
        ->assertSee(timestampMarkup($treasury->created_at), false)
        ->assertSee(timestampMarkup($treasury->updated_at), false)
        ->assertDontSeeText('master_marker');
});

test('users can find a treasury by its notes', function () {
    $actor = userWithPermissions(Permission::ViewTreasuries);
    Treasury::factory()->create(['name' => 'Front desk', 'notes' => 'Petty cash drawer']);
    Treasury::factory()->create(['name' => 'Shop cash', 'notes' => 'Retail float']);

    $this->actingAs($actor)
        ->get(route('treasuries.index', ['search' => 'Petty cash']))
        ->assertOk()
        ->assertSeeText('Front desk')
        ->assertSeeText('Petty cash drawer')
        ->assertDontSeeText('Shop cash');
});

test('the edit form shows who created and updated the treasury', function () {
    $actor = userWithPermissions(Permission::UpdateTreasuries);
    $creator = User::factory()->create(['name' => 'Lina Cashier']);
    $updater = User::factory()->create(['name' => 'Omar Auditor']);
    $treasury = Treasury::factory()->create([
        'notes' => 'Front desk cash',
        'created_by' => $creator->id,
        'updated_by' => $updater->id,
    ]);

    $this->actingAs($actor)
        ->get(route('treasuries.edit', $treasury))
        ->assertOk()
        ->assertSeeText('Front desk cash')
        ->assertSeeText('Lina Cashier')
        ->assertSeeText('Omar Auditor')
        ->assertSeeText($treasury->created_at->translatedFormat('d M Y, h:i A'))
        ->assertSeeText($treasury->updated_at->translatedFormat('d M Y, h:i A'))
        ->assertSee(timestampMarkup($treasury->created_at), false)
        ->assertSee(timestampMarkup($treasury->updated_at), false);
});

test('a percent sign in search does not match every treasury', function () {
    $actor = userWithPermissions(Permission::ViewTreasuries);
    Treasury::factory()->create(['name' => '100% Ready', 'notes' => null]);
    Treasury::factory()->create(['name' => 'Plain Cash', 'notes' => null]);

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
        ->and($treasury->parent_id)->toBeNull()
        ->and($treasury->created_by)->toBe($actor->id)
        ->and($treasury->updated_by)->toBe($actor->id);
});

test('users can add a sub-treasury under a main treasury', function () {
    $actor = userWithPermissions(Permission::CreateTreasuries, Permission::ViewTreasuries);
    $main = Treasury::factory()->create(['name' => 'Main cash']);

    $this->actingAs($actor)
        ->post(route('treasuries.store'), validTreasuryPayload([
            'code' => 'drawer-1',
            'name' => 'Drawer one',
            'parent_id' => $main->id,
        ]))
        ->assertRedirect(route('treasuries.index'))
        ->assertSessionHas('status');

    $subTreasury = Treasury::query()->where('code', 'DRAWER-1')->first();

    expect($subTreasury)->not->toBeNull()
        ->and($subTreasury->parent_id)->toBe($main->id)
        ->and($subTreasury->is_master)->toBeFalse()
        ->and($main->refresh()->children->first()->is($subTreasury))->toBeTrue();
});

test('the add form starts under the chosen main treasury', function () {
    $actor = userWithPermissions(Permission::CreateTreasuries);
    $main = Treasury::factory()->create(['code' => 'MAIN', 'name' => 'Main cash']);
    $subTreasury = Treasury::factory()->forParent($main)->create(['code' => 'DRAWER', 'name' => 'Drawer']);

    $this->actingAs($actor)
        ->get(route('treasuries.create', ['parent' => $main->id]))
        ->assertOk()
        ->assertSee('value="'.$main->id.'" selected', false)
        ->assertSee('MAIN - Main cash', false)
        ->assertDontSee('value="'.$subTreasury->id.'"', false);

    $this->actingAs($actor)
        ->get(route('treasuries.create', ['parent' => $subTreasury->id]))
        ->assertOk()
        ->assertSee('value="" selected', false)
        ->assertDontSee('value="'.$subTreasury->id.'"', false);
});

test('a sub-treasury cannot be the master', function () {
    $actor = userWithPermissions(Permission::CreateTreasuries);
    $main = Treasury::factory()->create();

    $this->actingAs($actor)
        ->from(route('treasuries.create'))
        ->post(route('treasuries.store'), validTreasuryPayload([
            'code' => 'DRAWER',
            'name' => 'Drawer',
            'parent_id' => $main->id,
            'is_master' => '1',
        ]))
        ->assertRedirect(route('treasuries.create'))
        ->assertSessionHasErrors('parent_id');

    expect(Treasury::query()->where('code', 'DRAWER')->exists())->toBeFalse();
});

test('a sub-treasury cannot contain another sub-treasury', function () {
    $actor = userWithPermissions(Permission::CreateTreasuries);
    $main = Treasury::factory()->create();
    $subTreasury = Treasury::factory()->forParent($main)->create();

    $this->actingAs($actor)
        ->from(route('treasuries.create'))
        ->post(route('treasuries.store'), validTreasuryPayload([
            'code' => 'DEEP',
            'name' => 'Too deep',
            'parent_id' => $subTreasury->id,
        ]))
        ->assertRedirect(route('treasuries.create'))
        ->assertSessionHasErrors('parent_id');

    expect(Treasury::query()->where('code', 'DEEP')->exists())->toBeFalse();
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

test('sub-treasuries are listed under their main treasury', function () {
    $actor = userWithPermissions(Permission::ViewTreasuries, Permission::CreateTreasuries);
    Treasury::factory()->create([
        'name' => 'Shop cash',
        'created_at' => now()->subMinute(),
    ]);
    $main = Treasury::factory()->create([
        'code' => 'MAIN',
        'name' => 'Main cash',
        'created_at' => now(),
    ]);
    $subTreasury = Treasury::factory()->forParent($main)->create([
        'code' => 'DRAWER',
        'name' => 'Drawer one',
        'opening_balance' => '25.00',
        'notes' => 'Front drawer',
    ]);

    $this->actingAs($actor)
        ->get(route('treasuries.index'))
        ->assertOk()
        ->assertSeeInOrder(['Main cash', 'Drawer one', 'Shop cash'])
        ->assertSeeText('DRAWER')
        ->assertSeeText('25.00')
        ->assertSeeText('Front drawer')
        ->assertSee(route('treasuries.create', ['parent' => $main->id]), false)
        ->assertDontSee(route('treasuries.create', ['parent' => $subTreasury->id]), false);
});

test('search finds a main treasury through one of its sub-treasuries', function () {
    $actor = userWithPermissions(Permission::ViewTreasuries);
    $main = Treasury::factory()->create(['name' => 'Main cash']);
    Treasury::factory()->forParent($main)->create([
        'name' => 'Drawer one',
        'notes' => 'Front drawer',
    ]);
    Treasury::factory()->create(['name' => 'Shop cash']);

    $this->actingAs($actor)
        ->get(route('treasuries.index', ['search' => 'Front drawer']))
        ->assertOk()
        ->assertSeeText('Main cash')
        ->assertSeeText('Drawer one')
        ->assertDontSeeText('Shop cash');
});

test('a sub-treasury can move under another main treasury', function () {
    $actor = userWithPermissions(Permission::UpdateTreasuries);
    $first = Treasury::factory()->create();
    $second = Treasury::factory()->create();
    $subTreasury = Treasury::factory()->forParent($first)->create();

    $this->actingAs($actor)
        ->put(route('treasuries.update', $subTreasury), validTreasuryPayload([
            'code' => $subTreasury->code,
            'name' => $subTreasury->name,
            'parent_id' => $second->id,
        ]))
        ->assertRedirect(route('treasuries.index'));

    expect($subTreasury->refresh()->parent_id)->toBe($second->id);
});

test('clearing the parent turns a sub-treasury into a main treasury', function () {
    $actor = userWithPermissions(Permission::UpdateTreasuries);
    $main = Treasury::factory()->create();
    $subTreasury = Treasury::factory()->forParent($main)->create(['name' => 'Drawer']);

    $this->actingAs($actor)
        ->put(route('treasuries.update', $subTreasury), validTreasuryPayload([
            'code' => $subTreasury->code,
            'name' => 'Drawer',
        ]))
        ->assertRedirect(route('treasuries.index'));

    expect($subTreasury->refresh()->parent_id)->toBeNull();
});

test('a main treasury cannot sit under itself or another treasury while it has sub-treasuries', function (string $parentKey) {
    $actor = userWithPermissions(Permission::UpdateTreasuries);
    $main = Treasury::factory()->create(['name' => 'Main cash']);
    $other = Treasury::factory()->create(['name' => 'Other cash']);
    Treasury::factory()->forParent($main)->create();

    $parentId = $parentKey === 'self' ? $main->id : $other->id;

    $this->actingAs($actor)
        ->from(route('treasuries.edit', $main))
        ->put(route('treasuries.update', $main), validTreasuryPayload([
            'code' => $main->code,
            'name' => $main->name,
            'parent_id' => $parentId,
        ]))
        ->assertRedirect(route('treasuries.edit', $main))
        ->assertSessionHasErrors('parent_id');

    expect($main->refresh()->parent_id)->toBeNull();
})->with([
    'itself' => 'self',
    'another treasury' => 'other',
]);

test('the edit form keeps a treasury with sub-treasuries as a main treasury', function () {
    $actor = userWithPermissions(Permission::UpdateTreasuries);
    $main = Treasury::factory()->create(['name' => 'Main cash']);
    Treasury::factory()->forParent($main)->create();

    $this->actingAs($actor)
        ->get(route('treasuries.edit', $main))
        ->assertOk()
        ->assertSeeText(__('treasuries.has_subs_hint'))
        ->assertDontSeeText(__('treasuries.parent_none'));
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

test('a main treasury with sub-treasuries cannot be deleted', function () {
    $actor = userWithPermissions(Permission::DeleteTreasuries, Permission::ViewTreasuries);
    $main = Treasury::factory()->create(['name' => 'Main cash']);
    $subTreasury = Treasury::factory()->forParent($main)->create(['name' => 'Drawer']);

    $this->actingAs($actor)
        ->delete(route('treasuries.destroy', $main))
        ->assertRedirect(route('treasuries.index'))
        ->assertSessionHas('error', __('treasuries.delete_blocked'));

    $this->assertModelExists($main);
    $this->assertModelExists($subTreasury);

    $this->actingAs($actor)
        ->get(route('treasuries.index'))
        ->assertOk()
        ->assertSeeText(__('treasuries.delete_blocked'))
        ->assertSee(route('treasuries.destroy', $subTreasury), false)
        ->assertSee('data-confirm="'.e(__('treasuries.confirm_delete')).'"', false)
        ->assertSee('id="confirm-dialog"', false)
        ->assertSeeText(__('general.confirm_irreversible'))
        ->assertDontSee('return confirm(', false);
});

test('users who can only view treasuries do not see the add sub-treasury action', function () {
    $actor = userWithPermissions(Permission::ViewTreasuries);
    Treasury::factory()->create();

    $this->actingAs($actor)
        ->get(route('treasuries.index'))
        ->assertOk()
        ->assertDontSeeText(__('treasuries.add_sub'));
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
