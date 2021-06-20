<?php

namespace Featica;

use Featica\Http\Middleware\AuthenticateDashboard;
use Featica\Http\Middleware\HandleInertiaRequests;

class Constants
{
    const PACKAGE_NAME = 'jackbrycesmith/featica';

    const DASHBOARD_MIDDLEWARE = [
        'web',
        AuthenticateDashboard::class,
        HandleInertiaRequests::class,
    ];
}
