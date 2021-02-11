<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function() {
	Route::get('/', function(){
		dd('testing');
	});
});

Route::middleware(['web'])->group(function() {
    Route::any('cart/add', 'Vanaheim\Core\Http\Controllers\CartController@add');

    /** @todo: this is only for local testing... */
    Route::get('token', function () {
         return response(json_encode(csrf_token()));
    });
});
