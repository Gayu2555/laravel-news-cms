<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReporterController;
use Illuminate\Support\Facades\Route;

// Redirect root ke halaman create article jika sudah login
Route::redirect('/', '/articles/create')->middleware(['auth']);

// Ganti definisi dashboard agar langsung menjalankan controller article create
Route::get('/dashboard', [ArticleController::class, 'create'])
    ->middleware(['auth'])
    ->name('dashboard');

// Grup route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Article Routes
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');

    // Category Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Reporter Routes
    Route::get('/reporters', [ReporterController::class, 'index'])->name('reporters.index');

    // API Routes for AJAX
    Route::get('/api/categories', [CategoryController::class, 'getCategories'])->name('api.categories');

    // Route Dummy untuk profile.edit
    Route::get('/profile/edit', function () {
        return redirect()->route('articles.create');
    })->name('profile.edit');
});

// Tambahkan route auth dari Breeze
require __DIR__ . '/auth.php';
