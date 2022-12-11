<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/clear-cache', function () {
    if (request()->query('appKey') && request()->query('appKey') === config('app.key')) {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return response('Cache cleared', 200);
    }

    return response('Invalid app key', 403);
})->name('clear-cache');
