<?php

use Featica\Featica;
use Featica\Tests\Models\Team;
use Illuminate\Support\Facades\Gate;
use Featica\Tests\Models\User;

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
        route('featica.model_list', $model)
    );

    $response->assertNotFound();
})->with([
    // Must be lowercase & set in Featica::
    'Users',
    'USERS',
    'blah',
    ''
]);

it('returns user list', function () {
    $users = User::factory()->count(50)->create();

    $response = $this->actingAs($this->adminUser)->get(
        route('featica.model_list', 'users')
    );

    $response->assertInertia(fn ($page) => $page
        ->component('Featica/ModelList')
        ->where('type', 'users')
        ->where('modelClass', User::class)
        ->has('models.data', 7)
    );
});

it('can be search filtered', function () {
    Featica::$owningModelDashboardSearch = [
        User::class => [
            'columns' => ['name', 'email']
        ]
    ];

    $users = User::factory()->count(50)->create();

    $response = $this->actingAs($this->adminUser)->get(
        route('featica.model_list', ['users', 'search' => $users->first()->email]),
    );

    $response->assertInertia(fn ($page) => $page
        ->component('Featica/ModelList')
        ->where('type', 'users')
        ->where('modelClass', User::class)
        ->where('models.data.0.email', $users->first()->email)
        ->has('models.data', 1)
    );
});
