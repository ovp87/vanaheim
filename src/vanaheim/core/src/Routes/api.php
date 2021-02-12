<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->prefix('api')->group(function(){

    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::post('sanctum', function() {

        $credentials = request(['email', 'password']);

        if (Auth::attempt($credentials)) {
            return response()->json(Auth::user()->createToken(request('name'), request('tokens'))->plainTextToken);
        }

        abort(401);
    });

});
