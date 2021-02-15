<?php

use Featica\Featica;
use Featica\Feature;
use Featica\Http\Middleware\WhenFeatureState;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

test('featica.when is available', function () {
    $routeMiddleware = resolve(Router::class)->getMiddleware();

    $this->assertTrue(Arr::has($routeMiddleware, 'featica.when'));
    expect(Arr::get($routeMiddleware, 'featica.when'))->toEqual('Featica\Http\Middleware\WhenFeatureState');
});

test('nicer syntax helper works: is/on/off', function () {
    // WhenFeatureState::is(...)
    expect(WhenFeatureState::is('feature-key'))->toBe(WhenFeatureState::class.':feature-key,on,403');

    // WhenFeatureState::on(...)
    expect(WhenFeatureState::on('feature-key'))->toBe(WhenFeatureState::class.':feature-key,on,403');
    expect(WhenFeatureState::on('feature-key', 404))->toBe(WhenFeatureState::class.':feature-key,on,404');
    expect(WhenFeatureState::on('feature-key', abortRedirectUrl: 'https://google.com'))->toBe(WhenFeatureState::class.':feature-key,on,403,https://google.com,');
    expect(WhenFeatureState::on('feature-key', abortRedirectRoute: 'named-route'))->toBe(WhenFeatureState::class.':feature-key,on,403,,named-route');

    // WhenFeatureState::off(...)
    expect(WhenFeatureState::off('feature-key'))->toBe(WhenFeatureState::class.':feature-key,off,403');
    expect(WhenFeatureState::off('feature-key', 404))->toBe(WhenFeatureState::class.':feature-key,off,404');
    expect(WhenFeatureState::off('feature-key', abortRedirectUrl: 'https://google.com'))->toBe(WhenFeatureState::class.':feature-key,off,403,https://google.com,');
    expect(WhenFeatureState::off('feature-key', abortRedirectRoute: 'named-route'))->toBe(WhenFeatureState::class.':feature-key,off,403,,named-route');
});

it('aborts if feature has not been registered', function () {
    Route::get('/featica-when-middleware-test', fn() => '👋')->middleware(WhenFeatureState::on('some-undefined-feature'));

    $response = $this->get('/featica-when-middleware-test');
    $response->assertStatus(403);
});

it('aborts if feature is off by default', function () {
    $feature = Featica::add(new Feature(key: 'feature-off-by-default', state: Feature::STATE_OFF));

    Route::get('/featica-when-middleware-test', fn() => '👋')->middleware(WhenFeatureState::on('feature-off-by-default'));

    $response = $this->get('/featica-when-middleware-test');
    $response->assertStatus(403);
});

it('aborts with redirect to url', function () {
    $route = Route::get('/featica-when-middleware-test', fn() => '👋')
        ->middleware(
            WhenFeatureState::on('some-undefined-feature', abortRedirectUrl: 'https://google.com')
        );

    $response = $this->get('/featica-when-middleware-test');
    $response->assertRedirect('https://google.com');
    $response->assertStatus(302);
});

it('aborts with redirect to named route', function () {
    Route::get('/named-url-test', fn() => '👋')->name('named-route-test');

    $route = Route::get('/featica-when-middleware-test', fn() => '👋')
        ->middleware(
            WhenFeatureState::on('some-undefined-feature', abortRedirectRoute: 'named-route-test')
        );

    $response = $this->get('/featica-when-middleware-test');
    $response->assertRedirect('/named-url-test');
    $response->assertStatus(302);
});

it('aborts with redirect with alternative status code', function () {
    Route::get('/named-url-test', fn() => '👋')->name('named-route-test');

    $route = Route::get('/featica-when-middleware-test', fn() => '👋')
        ->middleware(
            WhenFeatureState::on('some-undefined-feature', abortCode: 307, abortRedirectRoute: 'named-route-test')
        );

    $response = $this->get('/featica-when-middleware-test');
    $response->assertRedirect('/named-url-test');
    $response->assertStatus(307);
});

it('continues when middleware allows fpor when feature is off & the passed feature is off by default', function () {
    $feature = Featica::add(new Feature(key: 'feature-off-by-default', state: Feature::STATE_OFF));

    Route::get('/featica-when-middleware-test', fn() => '👋')->middleware(WhenFeatureState::off('feature-off-by-default'));

    $response = $this->get('/featica-when-middleware-test');
    $response->assertOk();
    $response->assertSee('👋');
});

it('continues when middleware allows for when feature is on & the passed feature is on by default', function () {
    $feature = Featica::add(new Feature(key: 'feature-on-by-default', state: Feature::STATE_ON));

    Route::get('/featica-when-middleware-test', fn() => '👋')->middleware(WhenFeatureState::on('feature-on-by-default'));

    $response = $this->get('/featica-when-middleware-test');
    $response->assertOk();
    $response->assertSee('👋');
});
