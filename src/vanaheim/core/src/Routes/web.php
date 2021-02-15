<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function() {

    Route::get('routes', function() {
        Artisan::call('route:list');
        dd(Artisan::output());
    });

    // get list of all supported locales, dd(ResourceBundle::getLocales(''));

    Route::get('token', function () {
        return response(json_encode(csrf_token()));
    });

    Route::post('cart/add', 'Vanaheim\Core\Http\Controllers\CartController@add');
    Route::post('cart/update', 'Vanaheim\Core\Http\Controllers\CartController@update');
});
