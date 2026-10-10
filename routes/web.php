<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Admin Routes ──
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/',                  [CategoryController::class, 'index'])   ->name('index');
        Route::get('/create',            [CategoryController::class, 'create'])  ->name('create');
        Route::post('/',                 [CategoryController::class, 'store'])   ->name('store');
        Route::get('/{category}',        [CategoryController::class, 'show'])    ->name('show');
        Route::get('/{category}/edit',   [CategoryController::class, 'edit'])    ->name('edit');
        Route::put('/{category}',        [CategoryController::class, 'update'])  ->name('update');
        Route::patch('/{category}',      [CategoryController::class, 'update'])  ->name('patch');
        Route::delete('/{category}',     [CategoryController::class, 'destroy']) ->name('destroy');
    });

    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/',                  [ProductController::class, 'index'])    ->name('index');
        Route::get('/create',            [ProductController::class, 'create'])   ->name('create');
        Route::post('/',                 [ProductController::class, 'store'])    ->name('store');
        Route::get('/{product}',         [ProductController::class, 'show'])     ->name('show');
        Route::get('/{product}/edit',    [ProductController::class, 'edit'])     ->name('edit');
        Route::put('/{product}',         [ProductController::class, 'update'])   ->name('update');
        Route::patch('/{product}',       [ProductController::class, 'update'])   ->name('patch');
        Route::delete('/{product}',      [ProductController::class, 'destroy'])  ->name('destroy');
    });

});

require __DIR__.'/auth.php';
