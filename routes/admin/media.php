<?php

use App\Http\Controllers\Admin\MediaController;
use Illuminate\Support\Facades\Route;

Route::prefix('media')->name('media.')->group(function () {
    Route::get('/', [MediaController::class, 'index'])->name('index')->middleware('permission:media.view');
    Route::get('/list', [MediaController::class, 'list'])->name('list')->middleware('permission:media.view');
    Route::post('/upload', [MediaController::class, 'upload'])->name('upload')->middleware('permission:media.create');
    Route::delete('/{id}', [MediaController::class, 'delete'])->name('delete')->middleware('permission:media.delete');
    Route::get('/modules', [MediaController::class, 'modules'])->name('modules')->middleware('permission:media.view');
});
