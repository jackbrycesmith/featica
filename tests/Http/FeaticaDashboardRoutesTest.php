<?php

use Featica\Featica;
use Featica\Feature;

beforeEach(function () {
    Featica::$features = [];
    Featica::auth(fn() => true);
});

it('does not share featica prop', function () {
    $response = $this->get(route('featica.index'));

    $response->assertInertia(fn ($page) => $page->missing('featica'));
});

it('shares full feature data', function () {
    $feature = Featica::add(new Feature(
        key: 'feature-1',
        state: Feature::STATE_OFF,
        meta: [
            'name' => 'Internal Name'
        ]
    ));

    $response = $this->get(route('featica.index'));

    $response->assertInertia(fn ($page) => $page
        ->has('features.0', fn ($page) => $page
            ->where('key', 'feature-1')
            ->where('state', 'off')
            ->where('meta.name', 'Internal Name')
            ->etc()
        )
    );
});
