<?php

use Featica\Featica;
use Featica\Feature;
use Featica\Tests\Models\User;

beforeEach(function () {
    Featica::$features = [];

    Featica::add(new Feature(
        key: 'global-enabled',
        type: Featica::TYPE_GLOBAL,
    ));
});

test('features can be registered', function () {
    Featica::$features = [];

    $feature1 = Featica::add(new Feature(key: 'feature-1'));
    $feature2 = Featica::add(new Feature(key: 'feature-2'));

    $this->assertInstanceOf(Feature::class, $feature1);
    $this->assertInstanceOf(Feature::class, $feature2);

    $this->assertEquals([
        'feature-1',
        'feature-2',
    ], array_keys(Featica::$features));

    $this->assertEquals([
        $feature1,
        $feature2
    ], array_values(Featica::$features));

});

test('feature can be found by key', function () {
    $feature = Featica::add(new Feature(
        key: 'feature-1',
    ));

    $foundFeature = Featica::find('feature-1');

    expect($foundFeature)->toEqual($feature);

});

// 'Do something when a user has a feature' (direct callback)
test('Featica::when returns correct callback for global enabled feature', function () {
    $user = User::factory()->create();

    $result = Featica::when(
        feature: 'global-enabled',
        model: $user,
        on: fn() => 'then result',
        off: fn() => 'otherwise result',
    );

    expect($result)->toBe('then result');

    $result = Featica::when(
        feature: 'global-enabled',
        on: fn() => 'then result',
        off: fn() => 'otherwise result',
    );

    expect($result)->toBe('then result');
});

test('Featica::when returns correct callback for global disabled feature', function () {
    $user = User::factory()->create();

    Featica::add(new Feature(
        key: 'global-disabled',
        state: Feature::STATE_OFF,
        type: Featica::TYPE_GLOBAL,
    ));

    $result = Featica::when(
        feature: 'global-disabled',
        model: $user,
        on: fn() => 'then result',
        off: fn() => 'otherwise result',
    );

    expect($result)->toBe('otherwise result');

    $result = Featica::when(
        feature: 'global-disabled',
        model: $user,
        on: 'then result',
        off: 'otherwise result',
    );

    expect($result)->toBe('otherwise result');
});

// Shareable data

test('correct inertia share data', function () {
    $feature = Featica::add(new Feature(
        key: 'feature-1',
        state: Feature::STATE_OFF,
        meta: [
            'name' => 'Internal Name'
        ]
    ));

    $sharedFeatureData = Featica::inertiaShareData();

    expect(key($sharedFeatureData))->toBe('features');

    $featureData = $sharedFeatureData['features']['feature-1'];

    expect(array_keys($featureData))->toEqual(['key', 'type', 'state']);

    expect($featureData)->toMatchArray([
        'key' => 'feature-1',
        'state' => Feature::STATE_OFF
    ]);
});
