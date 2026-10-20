<?php

use App\Http\Controllers\Frontend\RouteController;
use Illuminate\Support\Facades\Route;

Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        // Standard clean dynamic routes without .html (e.g., /menu-khach-le, /san-pham, /tin-tuc)
        Route::get('{slug}', [RouteController::class, 'resolve'])
            ->where(['slug' => '[a-zA-Z0-9\-]+'])
            ->name('resolve');

        // Backward compatibility for legacy links ending with .html
        Route::get('{slug}.html', [RouteController::class, 'resolve'])
            ->where(['slug' => '[a-zA-Z0-9\-]+'])
            ->name('resolve.html');
    });
