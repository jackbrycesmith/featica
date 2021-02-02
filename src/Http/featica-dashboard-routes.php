<?php

use Featica\Featica;
use Featica\Http\Controllers\ModelListController;
use Featica\Http\Controllers\ModelViewController;
use Featica\Http\Controllers\UpdateModelFeatureFlagsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return \Inertia\Inertia::render('Featica/Index');
})->name('featica.index');

Route::get('/feature/{feature}', function ($feature) {
    return \Inertia\Inertia::render('Featica/Feature', [
        'feature' => fn() => Featica::find($feature)
    ]);
})->name('featica.feature');

Route::get('/model/{model}', ModelListController::class)->name('featica.model_list');

Route::get('/model/{model}/{modelId}', ModelViewController::class)->name('featica.model_view');

Route::put('/model/{model}/{modelId}', UpdateModelFeatureFlagsController::class)->name('featica.model_view_update');
