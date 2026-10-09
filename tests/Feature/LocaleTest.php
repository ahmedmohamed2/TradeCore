<?php

use App\Models\User;

test('authenticated users with the default locale see the arabic layout', function () {
    $user = User::factory()->create();

    expect($user->locale)->toBe('ar');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('dir="rtl"', false)
        ->assertSeeText(__('menu.dashboard'));
});

test('guests persist the selected locale in the session', function () {
    $this->from(route('login'))
        ->post(route('locale.update'), ['locale' => 'en'])
        ->assertRedirect(route('login'));

    $this->get(route('login'))
        ->assertOk()
        ->assertSee('dir="ltr"', false)
        ->assertSeeText(__('Sign in to start your session'));
});

test('authenticated users can switch locale to english', function () {
    $user = User::factory()->create(['locale' => 'ar']);

    $this->actingAs($user)
        ->from(route('dashboard'))
        ->post(route('locale.update'), ['locale' => 'en'])
        ->assertRedirect(route('dashboard'));

    expect($user->fresh()->locale)->toBe('en');
    expect(session('locale'))->toBe('en');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('dir="ltr"', false)
        ->assertSeeText(__('menu.dashboard'));
});

test('invalid locales are rejected', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('dashboard'))
        ->post(route('locale.update'), ['locale' => 'fr'])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHasErrors('locale');

    expect($user->fresh()->locale)->toBe('ar');
});

test('the login page uses arabic by default', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('dir="rtl"', false)
        ->assertSeeText(__('Sign in to start your session'));
});
