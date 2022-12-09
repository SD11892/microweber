<?php

use Illuminate\Support\Facades\Route;

\Route::name('api.live_edit.')

    ->prefix('api.live_edit')
    ->middleware(['api', 'admin', 'xss'])
    ->namespace('\MicroweberPackages\LiveEdit\Http\Controllers\Api')
    ->group(function () {

        Route::get('', function () {

        });

    });
