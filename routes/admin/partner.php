<?php

use App\Http\Controllers\Admin\PartnerController;
use Illuminate\Support\Facades\Route;

Route::controller(PartnerController::class)
    ->prefix('partner')
    ->name('partner.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:partner.view|brand.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:partner.create|brand.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:partner.edit|brand.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:partner.create|brand.create');
        Route::get('/edit/{uuid}/{page?}', 'edit')->name('edit')->middleware('permission:partner.edit|brand.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:partner.edit|brand.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:partner.delete|brand.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:partner.delete|brand.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:partner.edit|brand.edit');
    });
