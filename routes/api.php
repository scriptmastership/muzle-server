<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    Route::post('login',   'AuthController@login');
    Route::middleware(['auth:api'])->group(function() {
        Route::post('logout',  'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
    });
});

Route::resource('tenants', TenantController::class)->except(['create', 'edit'])->middleware('userscope:admin');
Route::resource('users', UserController::class)->except(['create', 'edit'])->middleware('userscope:admin');