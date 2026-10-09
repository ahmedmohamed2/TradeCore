<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertSuccessful()
        ->assertSee('login-page', false)
        ->assertSee('login-box', false)
        ->assertSeeText(__('Sign in to start your session'));
});

test('login screen does not offer registration or remember me', function () {
    $this->get('/login')
        ->assertSuccessful()
        ->assertDontSeeText(__('Register a new membership'))
        ->assertDontSeeText(__('Remember me'))
        ->assertDontSee('name="remember"', false);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('login does not persist a remember cookie', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'remember' => 'on',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false))
        ->assertCookieMissing(Auth::guard()->getRecallerName());
});

test('users cannot authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});
