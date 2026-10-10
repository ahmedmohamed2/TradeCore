<?php

use App\Models\Treasury;
use App\Models\User;

test('a treasury belongs to the users who created and updated it', function () {
    $creator = User::factory()->create();
    $updater = User::factory()->create();

    $treasury = Treasury::factory()->create([
        'created_by' => $creator->id,
        'updated_by' => $updater->id,
    ]);

    expect($treasury->createdBy)->toBeInstanceOf(User::class)
        ->and($treasury->createdBy->is($creator))->toBeTrue()
        ->and($treasury->updatedBy)->toBeInstanceOf(User::class)
        ->and($treasury->updatedBy->is($updater))->toBeTrue();
});

test('a user has the treasuries they created and updated', function () {
    $user = User::factory()->create();

    $createdTreasury = Treasury::factory()->create([
        'created_by' => $user->id,
    ]);

    $updatedTreasury = Treasury::factory()->create([
        'updated_by' => $user->id,
    ]);

    expect($user->createdTreasuries)->toHaveCount(1)
        ->and($user->createdTreasuries->first()->is($createdTreasury))->toBeTrue()
        ->and($user->updatedTreasuries)->toHaveCount(1)
        ->and($user->updatedTreasuries->first()->is($updatedTreasury))->toBeTrue();
});

test('a master treasury stores the single master marker', function () {
    $treasury = Treasury::factory()->master()->create();

    expect($treasury->is_master)->toBeTrue()
        ->and($treasury->master_marker)->toBe(1);

    $treasury->update(['is_master' => false]);

    expect($treasury->is_master)->toBeFalse()
        ->and($treasury->master_marker)->toBeNull();
});
