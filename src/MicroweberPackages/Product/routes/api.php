<?php

Route::name('api.')
    ->prefix('api')
    ->middleware(['api'])
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Api')
    ->group(function () {
        Route::apiResource('product', 'ProductApiController');
    });

