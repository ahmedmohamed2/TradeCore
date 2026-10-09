<?php

use App\Models\SystemSetting;
use App\Models\User;

test('a system setting belongs to the users who created and updated it', function () {
    $creator = User::factory()->create();
    $updater = User::factory()->create();

    $setting = SystemSetting::factory()->create([
        'created_by' => $creator->id,
        'updated_by' => $updater->id,
    ]);

    expect($setting->createdBy)->toBeInstanceOf(User::class)
        ->and($setting->createdBy->is($creator))->toBeTrue()
        ->and($setting->updatedBy)->toBeInstanceOf(User::class)
        ->and($setting->updatedBy->is($updater))->toBeTrue();
});

test('a user has the system settings they created and updated', function () {
    $user = User::factory()->create();

    $createdSetting = SystemSetting::factory()->create([
        'created_by' => $user->id,
    ]);

    $updatedSetting = SystemSetting::factory()->create([
        'updated_by' => $user->id,
    ]);

    expect($user->createdSystemSettings)->toHaveCount(1)
        ->and($user->createdSystemSettings->first()->is($createdSetting))->toBeTrue()
        ->and($user->updatedSystemSettings)->toHaveCount(1)
        ->and($user->updatedSystemSettings->first()->is($updatedSetting))->toBeTrue();
});
