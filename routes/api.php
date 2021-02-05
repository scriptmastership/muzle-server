<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    
    Route::post('login', 'AuthController@login');
    
    Route::middleware(['auth:api'])->group(function() {
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
    });

});

Route::prefix('admin')->group(function() {
    Route::middleware(['auth:api'])->group(function() {
        
        Route::resource('tenants', TenantController::class)->except(['create', 'edit'])->middleware('userscope:admin');

        Route::resource('users', UserController::class)->except(['create', 'edit'])->middleware('userscope:admin');

        Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'update'])->middleware('userscope:admin');
        Route::post('categories/update/{id}', 'CategoryController@update')->middleware('userscope:admin');
        
        Route::resource('images', ImageController::class)->except(['create', 'edit', 'update'])->middleware('userscope:admin');
        Route::post('images/update/{id}', 'ImageController@update')->middleware('userscope:admin');

    });
});
