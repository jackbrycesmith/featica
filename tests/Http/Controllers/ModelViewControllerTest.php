<?php

use Featica\Featica;
use Featica\Tests\Models\Team;
use Featica\Tests\Models\User;
use Illuminate\Support\Facades\Gate;

beforeEach(function () {
    $this->adminUser = User::factory()->create(['email' => 'admin@test.test']);
    Featica::setOwningModel(key: 'user', modelClass: User::class)->setOwningModel(key: 'team', modelClass: Team::class);

    Gate::define('viewFeatica', function ($user) {
        return in_array($user->email, [
            $this->adminUser->email
        ]);
    });
});

it('404s if model not listed as having feature flags', function ($model) {
    $response = $this->actingAs($this->adminUser)->get(
        route('featica.model_view', [$model, ''])
    );

    $response->assertNotFound();
})->with([
    // Must be lowercase & set in Featica::
    'Users',
    'USERS',
    'blah',
    ''
]);

it('404s if model not found', function () {
    $response = $this->actingAs($this->adminUser)->get(
        route('featica.model_view', ['users', 'nonExistentModelId'])
    );

    $response->assertNotFound();
});

it('returns model view', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->adminUser)->get(
        route('featica.model_view', ['users', $user->id])
    );

    $response->assertInertia(fn ($page) => $page
        ->component('Featica/ModelView')
        ->where('type', 'users')
        ->where('modelClass', User::class)
        ->where('model.id', $user->id)
    );
});
