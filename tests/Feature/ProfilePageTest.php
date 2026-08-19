<?php

use App\Models\User;

test('guests are redirected from the profile page to login', function () {
    $this->get(route('profile.show'))
        ->assertRedirect(route('login'));
});

test('authenticated users can visit the profile page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertSee('app-wrapper', false)
        ->assertSee('card-title', false)
        ->assertSee('card-tools', false)
        ->assertSeeText('Profile Information')
        ->assertSeeText("Update your account's profile information and email address.")
        ->assertSeeText('Update Password')
        ->assertSeeText('Ensure your account is using a long, random password to stay secure.');
});
