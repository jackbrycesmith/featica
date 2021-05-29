<?php

use Featica\Featica;
use Featica\FeaticaManager;

test('featica helper returns an instance of FeaticaManager', function () {
    expect(featica())->toBeInstanceOf(FeaticaManager::class);
});

test('featica helper is same as facade instance', function () {
    Featica::setDefaultOwner('same');
    expect(featica()->getDefaultOwner())->toBe('same');
});
