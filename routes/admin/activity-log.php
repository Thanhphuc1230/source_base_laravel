<?php

use App\Http\Controllers\Admin\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::controller(ActivityLogController::class)
    ->prefix('activity-log')
    ->name('activity_log.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:activity_log.view');
        Route::get('/{id}', 'show')->name('show')->middleware('permission:activity_log.view');
        Route::post('/clean', 'clean')->name('clean')->middleware('permission:activity_log.delete');
        Route::delete('/{id}', 'delete')->name('destroy')->middleware('permission:activity_log.delete');
    });
