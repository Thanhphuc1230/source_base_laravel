<?php

use App\Http\Controllers\Frontend\ContactController as Contact;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'home'])->name('home');

        // Contact
        Route::get('/lien-he.html', [Contact::class, 'contact'])->name('contact');
        Route::get('/lien-he', [Contact::class, 'contact'])->name('contact.clean');
        Route::post('/gui-yeu-cau-lien-he', [Contact::class, 'postContact'])->name('postContact');

        // Factory Page
        Route::get('/nha-may', [HomeController::class, 'factory'])->name('factory');
        Route::get('/nha-xuong', [HomeController::class, 'factory'])->name('factory.alias');

        // Thiet ke thi cong shortcut
        Route::get('/thiet-ke-thi-cong', function() {
            $cate = \App\Models\CateProject::where('status', 1)->first();
            if ($cate) {
                $slug = $cate->slug_vn ?: $cate->slug;
                return redirect('/' . $slug);
            }
            return redirect()->route('web.home');
        })->name('thiet-ke-thi-cong');

        // San pham shortcut
        Route::get('/san-pham', function() {
            $cate = \App\Models\CateProduct::where('status', 1)->first();
            if ($cate) {
                $slug = $cate->slug_vn ?: $cate->slug;
                return redirect('/' . $slug);
            }
            return redirect()->route('web.home');
        })->name('products.all');
        Route::get('/san-pham.html', function() {
            $cate = \App\Models\CateProduct::where('status', 1)->first();
            if ($cate) {
                $slug = $cate->slug_vn ?: $cate->slug;
                return redirect('/' . $slug);
            }
            return redirect()->route('web.home');
        });

        // Subscribe
        Route::post('/subscribe', [Contact::class, 'postSubscribe'])->name('postSubscribe');
        
        // 404 error page
        Route::get('/404', function() {
            return view('errors.404');
        })->name('404');
    });
