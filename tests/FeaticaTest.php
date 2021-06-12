<?php

use Featica\Featica;
use Featica\Feature;
use Featica\Tests\Models\User;

beforeEach(function () {
    Featica::clearDefinedFeatures();

    Featica::add(new Feature(
        key: 'global-enabled',
        type: Featica::TYPE_GLOBAL,
    ));
});

test('features can be registered', function () {
    Featica::clearDefinedFeatures();

    $feature1 = Featica::add(new Feature(key: 'feature-1'));
    $feature2 = Featica::add(new Feature(key: 'feature-2'));

    $this->assertInstanceOf(Feature::class, $feature1);
    $this->assertInstanceOf(Feature::class, $feature2);

    $this->assertEquals([
        'feature-1',
        'feature-2',
    ], array_keys(Featica::definedFeatures()));

    $this->assertEquals([
        $feature1,
        $feature2
    ], array_values(Featica::definedFeatures()));

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
        for: $user,
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
        for: $user,
        on: fn() => 'then result',
        off: fn() => 'otherwise result',
    );

    expect($result)->toBe('otherwise result');

    $result = Featica::when(
        feature: 'global-disabled',
        for: $user,
        on: 'then result',
        off: 'otherwise result',
    );

    expect($result)->toBe('otherwise result');
});

// Featica::stateOf

test('Featica::stateOf returns correct state for given feature key', function () {
    expect(Featica::stateOf('undefinedFeature'))->toBe('off');
    expect(Featica::stateOf('undefinedFeature', null))->toBeNull();

    Featica::add(new Feature(key: 'feature-off-by-default', state: Feature::STATE_OFF));
    expect(Featica::stateOf('feature-off-by-default'))->toBe('off');
    expect(Featica::stateOf('feature-off-by-default', null))->toBe('off');

    Featica::add(new Feature(key: 'feature-on-by-default', state: Feature::STATE_ON));
    expect(Featica::stateOf('feature-on-by-default'))->toBe('on');
});

test('Featica::inertiaShareData() works', function () {
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

test('Featica::setDefaultOwner(...) works', function () {
    expect(Featica::getDefaultOwner())->toBeNull();

    Featica::setDefaultOwner('Default Owner');

    expect(Featica::getDefaultOwner())->toBe('Default Owner');
});
