<?php

use App\Enums\Permission;
use App\Models\Treasury;
use App\Models\User;
use App\Support\RealtimeSearch;

test('directory pages include realtime search that uses the fetch api', function (Permission $permission, string $route) {
    $script = file_get_contents(public_path('assets/js/realtime-search.js'));

    expect($script)
        ->toContain('fetch(')
        ->not->toContain('XMLHttpRequest');

    $this->actingAs(userWithPermissions($permission))
        ->get(route($route))
        ->assertOk()
        ->assertSee('data-realtime-search', false)
        ->assertSee('assets/js/realtime-search.js', false);
})->with([
    'users' => [Permission::ViewUsers, 'users.index'],
    'treasuries' => [Permission::ViewTreasuries, 'treasuries.index'],
]);

test('user search can return only the results fragment', function () {
    $actor = userWithPermissions(Permission::ViewUsers);
    User::factory()->create(['name' => 'Nadia Search', 'email' => 'nadia@example.com']);
    User::factory()->create(['name' => 'Other Person', 'email' => 'other@example.com']);

    $this->actingAs($actor)
        ->get(route('users.index', ['search' => 'Nadia']), [RealtimeSearch::Header => '1'])
        ->assertOk()
        ->assertHeader(RealtimeSearch::Header, '1')
        ->assertSeeText('Nadia Search')
        ->assertSeeText('nadia@example.com')
        ->assertDontSeeText('Other Person')
        ->assertDontSeeText(__('users.search_placeholder'))
        ->assertDontSeeText(__('general.footer_tagline'));
});

test('treasury search can return only the results fragment', function () {
    $actor = userWithPermissions(Permission::ViewTreasuries);
    Treasury::factory()->create(['code' => 'MAIN', 'name' => 'Main cash']);
    Treasury::factory()->create(['code' => 'SHOP', 'name' => 'Shop cash']);

    $this->actingAs($actor)
        ->get(route('treasuries.index', ['search' => 'Main']), [RealtimeSearch::Header => '1'])
        ->assertOk()
        ->assertHeader(RealtimeSearch::Header, '1')
        ->assertSeeText('MAIN')
        ->assertSeeText('Main cash')
        ->assertDontSeeText('Shop cash')
        ->assertDontSeeText(__('treasuries.search_placeholder'))
        ->assertDontSeeText(__('general.footer_tagline'));
});

test('a percent sign in a realtime search does not match every record', function (Permission $permission, string $route) {
    $actor = userWithPermissions($permission);

    if ($route === 'users.index') {
        User::factory()->create(['name' => '100% Ready']);
        User::factory()->create(['name' => 'Plain Name']);
        $match = '100% Ready';
        $other = 'Plain Name';
    } else {
        Treasury::factory()->create(['name' => '100% Ready']);
        Treasury::factory()->create(['name' => 'Plain Cash']);
        $match = '100% Ready';
        $other = 'Plain Cash';
    }

    $this->actingAs($actor)
        ->get(route($route, ['search' => '%']), [RealtimeSearch::Header => '1'])
        ->assertOk()
        ->assertSeeText($match)
        ->assertDontSeeText($other);
})->with([
    'users' => [Permission::ViewUsers, 'users.index'],
    'treasuries' => [Permission::ViewTreasuries, 'treasuries.index'],
]);

test('realtime search still requires permission', function (string $route) {
    $this->actingAs(User::factory()->create())
        ->get(route($route), [RealtimeSearch::Header => '1'])
        ->assertForbidden();
})->with([
    'users' => ['users.index'],
    'treasuries' => ['treasuries.index'],
]);
