<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Vanaheim\Core\Models\User;
use Illuminate\Support\Facades\DB;


Route::middleware(['web'])->group(function() {

    Route::get('routes', function() {
        Artisan::call('route:list');
        dd(Artisan::output());
    });

    Route::get('token', function () {
        return response(json_encode(csrf_token()));
    });

    Route::post('cart/add', 'Vanaheim\Core\Http\Controllers\CartController@add');
});
