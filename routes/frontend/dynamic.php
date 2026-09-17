<?php

use App\Http\Controllers\Frontend\RouteController;
use Illuminate\Support\Facades\Route;

Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        // Handle all dynamic routes with ID-slug format (.html)
        Route::get('{slug}.html', [RouteController::class, 'resolve'])
            ->where(['slug' => '[a-zA-Z0-9\-]+'])
            ->name('resolve');

        // Handle clean slugs without .html (e.g., /villa, /dinh-thu, /khach-san, /showroom, /tin-tuc)
        Route::get('{slug}', [RouteController::class, 'resolve'])
            ->where(['slug' => '[a-zA-Z0-9\-]+'])
            ->name('resolve.clean');
    });
