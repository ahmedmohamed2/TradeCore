<?php

use App\Models\SystemSetting;
use App\Models\User;

test('guests are redirected to the login page from system settings', function () {
    $this->get(route('system-settings.index'))->assertRedirect(route('login'));
});

test('authenticated users can visit system settings', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('system-settings.index'))
        ->assertOk();
});

test('authenticated users can view the active system settings', function () {
    $user = User::factory()->create();
    $setting = SystemSetting::factory()->create([
        'system_name' => 'TradeCore',
        'active' => true,
    ]);

    $this->actingAs($user)
        ->get(route('system-settings.index'))
        ->assertOk()
        ->assertSeeText('TradeCore')
        ->assertSeeText('Edit')
        ->assertSee(route('system-settings.edit', $setting), false);
});
