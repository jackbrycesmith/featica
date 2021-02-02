<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Featica Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where the Featica Dashboard will be accessible from.
    | If this setting is null, Featica will reside under the same domain as the
    | application. Otherwise, this value will serve as the subdomain.
    |
    */
    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | Featica Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where the Featica Dashboard will be accessible from.
    |
    */

    'path' => 'featica',

    /*
    |--------------------------------------------------------------------------
    | Featica Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will get attached onto each Featica route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => \Featica\Constants::DASHBOARD_MIDDLEWARE,
];
