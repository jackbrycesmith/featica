<?php

use Featica\Featica;
use Featica\Tests\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

test('access denied on clean install', function () {
    $response = $this->get(route('featica.index'));
    $response->assertForbidden();
});

test('access denied for any auth user', function () {
    $this->actingAs(new User());
    $response = $this->get(route('featica.index'));
    $response->assertForbidden();
});

test('access denied for guest, when auth user is required by gate', function () {
    Featica::auth(function (Request $request) {
        return Gate::check('viewFeatica', [$request->user()]);
    });

    Gate::define('viewFeatica', function ($user) {
        return true;
    });

    $response = $this->get(route('featica.index'));
    $response->assertForbidden();
});

test('access denied in simple closure check', function () {
    Featica::auth(fn() => false);

    $response = $this->get(route('featica.index'));
    $response->assertForbidden();
});

test('access allowed in simple closure check', function () {
    Featica::auth(fn() => true);

    $response = $this->get(route('featica.index'));
    $response->assertOk();
});

test('access allowed when user is specifically allowed by gate', function () {
    $this->actingAs($user = User::factory()->create(['email' => 'test@featica.com']));

    Featica::auth(function (Request $request) {
        return Gate::check('viewFeatica', [$request->user()]);
    });

    Gate::define('viewFeatica', function (Authenticatable $user) {
        return $user->email === 'test@featica.com';
    });

    $response = $this->get(route('featica.index'));
    $response->assertOk();
});

test('access allowed for guests when nullable in gate', function () {
    Featica::auth(function (Request $request) {
        return Gate::check('viewFeatica', [$request->user()]);
    });

    Gate::define('viewFeatica', function (?Authenticatable $user) {
        return true;
    });

    $response = $this->get(route('featica.index'));
    $response->assertOk();
});
