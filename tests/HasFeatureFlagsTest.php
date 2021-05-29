<?php

use Featica\Featica;
use Featica\Feature;
use Featica\Tests\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Featica::clearDefinedFeatures();

    Featica::add(new Feature(
        key: 'global-enabled',
        type: Featica::TYPE_GLOBAL,
    ));
});

test('hasFeature returns true for global enabled feature, with empty feature_flags', function () {
    $user = User::factory()->create();

    $this->assertEmpty($user->feature_flags);

    $this->assertTrue($user->hasFeature('global-enabled'));
});

test('hasFeature returns true for locally enabled feature, with empty feature_flags', function () {
    Featica::add(new Feature(
        key: 'local-enabled',
        state: Feature::STATE_ON,
        type: Featica::TYPE_USER,
    ));

    $user = User::factory()->create();

    expect($user->feature_flags)->toBeEmpty();
    expect($user->hasFeature('local-enabled'))->toBeTrue();
});

test('hasFeature returns true for initially disabled feature, that the model has enabled', function () {
    Featica::add(new Feature(
        key: 'initially-disabled',
        state: Feature::STATE_OFF,
        type: Featica::TYPE_USER,
    ));

    $user = User::factory()->enabledFeatures('initially-disabled')->create();

    expect($user->hasFeature('initially-disabled'))->toBeTrue();
});

test('hasFeature returns false for initially enabled feature, that the model has disabled', function () {
    Featica::add(new Feature(
        key: 'initially-enabled',
        state: Feature::STATE_ON,
        type: Featica::TYPE_USER,
    ));

    $user = User::factory()
        ->disabledFeatures('initially-enabled')
        ->create();

    expect($user->hasFeature('initially-enabled'))->toBeFalse();
});

// scopeFeaticaDashboardSearch
test('searchableColumnsForFeaticaDashboard filters out non existant columns', function () {
    $searchableColumns = (new User)->searchableColumnsForFeaticaDashboard();

    expect($searchableColumns)->toHaveCount(3);
    expect($searchableColumns)->toBe(['id', 'name', 'email']);
});

test('scopeFeaticaDashboardSearch works', function () {
    Featica::setOwningModelDashboardSearch([
        User::class => [
            'columns' => ['name', 'email']
        ]
    ]);

    $salah = User::factory()->create(['name' => 'Salah', 'email' => 'salah@lfc.com']);
    $mane = User::factory()->create(['name' => 'Mane', 'email' => 'mane@lfc.com']);

    // Sanity check
    $modelColumns = Schema::getColumnListing($salah->getTable());
    expect($modelColumns)->toContain('name');
    expect($modelColumns)->toContain('email');

    $termsToOnlyFindSalah = ['s', 'salah', 'SaL', 'salah@LFC.com'];
    foreach ($termsToOnlyFindSalah as $salahSearch) {
        $salahSearchResult = User::featicaDashboardSearch($salahSearch)->get();
        expect($salahSearchResult->count())->toBe(1);
        expect($salahSearchResult->first()->toArray())->toMatchArray($salah->toArray());
    }

    $termsToOnlyFindMane = ['ma', 'man', 'MANe'];
    foreach ($termsToOnlyFindMane as $maneSearch) {
        $maneSearchResult = User::featicaDashboardSearch($maneSearch)->get();
        expect($maneSearchResult->count())->toBe(1);
        expect($maneSearchResult->first()->toArray())->toMatchArray($mane->toArray());
    }

    $termsToFindBoth = ['@lfc.com', 'lfc', '.com'];
    foreach ($termsToFindBoth as $bothSearch) {
        $bothSearchResult = User::featicaDashboardSearch($bothSearch)->get();
        expect($bothSearchResult->count())->toBe(2);
    }
});
