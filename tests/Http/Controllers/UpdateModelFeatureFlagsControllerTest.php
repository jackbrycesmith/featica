<?php

use Featica\Featica;
use Featica\Feature;
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
        route('featica.model_view_update', [$model, ''])
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
        route('featica.model_view_update', ['users', 'nonExistentModelId'])
    );

    $response->assertNotFound();
});


it('updates model view', function () {
    $user = User::factory()->create();
    $feature = Featica::add(new Feature(key: 'feature-1'));
    $feature2 = Featica::add(new Feature(key: 'feature-2'));

    $response = $this->actingAs($this->adminUser)->put(
        route('featica.model_view_update', ['users', $user->id]),
        [
            'feature_flags' => [
                'feature-1' => 'off',
                'feature-2' => 'on'
            ]
        ]
    );

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'feature_flags' => json_encode(['feature-1' => 'off', 'feature-2' => 'on'])
    ]);
});

