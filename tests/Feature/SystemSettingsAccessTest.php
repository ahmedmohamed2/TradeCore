<?php

use App\Enums\Permission;
use App\Models\SystemSetting;
use App\Models\User;

test('guests are redirected to the login page from system settings', function () {
    $this->get(route('system-settings.index'))->assertRedirect(route('login'));
});

test('users without permission cannot view system settings', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('system-settings.index'))
        ->assertForbidden();
});

test('users with permission can visit system settings', function () {
    $user = userWithPermissions(Permission::ViewSystemSettings);

    $this->actingAs($user)
        ->get(route('system-settings.index'))
        ->assertOk();
});

test('users with permission can view the active system settings', function () {
    $user = userWithPermissions(Permission::ViewSystemSettings, Permission::UpdateSystemSettings);
    $setting = SystemSetting::factory()->create([
        'system_name' => 'TradeCore',
        'active' => true,
    ]);

    $this->actingAs($user)
        ->get(route('system-settings.index'))
        ->assertOk()
        ->assertSeeText('TradeCore')
        ->assertSeeText(__('general.edit'))
        ->assertSeeText(__('system-settings.section_contact'))
        ->assertSeeText(__('system-settings.section_notice'))
        ->assertSee(route('system-settings.edit', $setting), false);
});

test('users who can only view system settings cannot open the edit form', function () {
    $user = userWithPermissions(Permission::ViewSystemSettings);
    $setting = SystemSetting::factory()->create();

    $this->actingAs($user)
        ->get(route('system-settings.index'))
        ->assertOk()
        ->assertDontSee(route('system-settings.edit', $setting), false);

    $this->actingAs($user)
        ->get(route('system-settings.edit', $setting))
        ->assertForbidden();
});
