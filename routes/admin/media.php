<?php

use App\Http\Controllers\Admin\MediaController;
use Illuminate\Support\Facades\Route;

Route::prefix('media')->name('media.')->group(function () {
    Route::get('/', [MediaController::class, 'index'])->name('index');
    Route::get('/list', [MediaController::class, 'list'])->name('list');
    Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
    Route::delete('/{id}', [MediaController::class, 'delete'])->name('delete');
    Route::get('/modules', [MediaController::class, 'modules'])->name('modules');
});
