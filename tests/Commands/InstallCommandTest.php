<?php

use Featica\Commands\InstallCommand;

it('is available when config/featica.php is not set', function () {
    app()['config']->offsetUnset('featica');
    $installCommand = resolve(InstallCommand::class);

    expect($installCommand->isHidden())->toBeFalse();
});

it('is hidden after first install', function () {
    app()['config']->set('featica', []);
    $installCommand = resolve(InstallCommand::class);

    expect($installCommand->isHidden())->toBeTrue();
});
