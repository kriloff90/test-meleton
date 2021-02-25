<?php

Route::post('login', 'V1\UserController@login');

Route::middleware('auth:api')->group(function () {
    Route::prefix('v1')->namespace('V1')->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', 'UserController@show');
            Route::post('logout', 'UserController@logout');

            Route::get('converts', 'CurrencyController@index');
        });

        Route::get('rates', 'CurrencyController@rates');
        Route::post('convert', 'CurrencyController@convert');
    });
});
