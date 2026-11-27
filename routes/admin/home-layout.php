<?php

use App\Http\Controllers\Admin\HomeLayoutController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeLayoutController::class)
    ->prefix('home-layout')
    ->name('home-layout.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:system.view');
        Route::post('/store', 'store')->name('store')->middleware('permission:system.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:system.edit');
        Route::post('/status/{uuid}/{status}', 'status')->name('status')->middleware('permission:system.edit');
        Route::post('/reorder', 'reorder')->name('reorder')->middleware('permission:system.edit');
        Route::delete('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:system.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy.get')->middleware('permission:system.edit');
    });
