<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::whereNumber([
    'id',
    'userId'
])
    ->middleware(['api'])
    ->group(function () {

        Route::controller(AuthController::class)
            ->prefix('auth')
            ->group(function () {
                Route::middleware('guest')
                    ->group(function () {
                        Route::post('/login', 'login');

                        Route::post('/register', 'store');

                        /*Route::post('/forgot-password', 'forgotPassword');

                        Route::middleware('check.password.reset.token')
                            ->group(function () {
                                Route::post('/validate-code', 'validatePasswordResetCode');
                                Route::post('/reset-password', 'resetPassword');
                            });*/
                    });


                Route::middleware([
                    'auth:sanctum',
                    'ensures.active.user'
                ])
                    ->group(function () {
                        Route::get('/refresh-token', 'refreshToken');
                        Route::get('/logout', 'logout');
                    });
            });

    });
