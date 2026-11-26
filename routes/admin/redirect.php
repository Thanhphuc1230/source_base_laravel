<?php

use App\Http\Controllers\Admin\RedirectController;
use Illuminate\Support\Facades\Route;

Route::controller(RedirectController::class)
    ->prefix('redirect')
    ->name('redirect.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:system.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:system.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:system.edit');
        Route::get('/edit/{uuid}', 'edit')->name('edit')->middleware('permission:system.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:system.edit');
        Route::post('/status/{uuid}/{status}', 'status')->name('status')->middleware('permission:system.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:system.edit');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:system.edit');
    });
