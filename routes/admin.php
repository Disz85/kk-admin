<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ArticleStatusController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\AutocompleteController;
use App\Http\Controllers\Admin\BrandChangeRequestController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FavoriteProductController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProductChangeRequestController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['permission:manage-admin'])->group(function () {
    Route::middleware(['permission:manage-authors'])->resource('authors', AuthorController::class)->except(['create', 'edit']);

    Route::middleware(['permission:show-users'])->resource('users', UserController::class)->only(['index', 'show']);

    Route::middleware(['permission:manage-tags'])->resource('tags', TagController::class)->except(['create', 'edit']);

    Route::middleware(['permission:manage-brands'])->resource('brands', BrandController::class)->except(['create', 'edit']);

    Route::middleware(['permission:manage-categories'])->group(function () {
        Route::get(
            'categories/get-types',
            [CategoryController::class, 'getTypes']
        );
        Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
    });

    Route::middleware(['permission:manage-products'])->resource('products', ProductController::class)->except(['create', 'edit']);

    Route::middleware(['permission:manage-ingredients'])->group(function () {
        Route::get('ingredients/get-ewg-data-types', [IngredientController::class, 'getEwgDataTypes']);
        Route::get('ingredients/get-categories', [IngredientController::class, 'getCategories']);
        Route::resource('ingredients', IngredientController::class)->except(['create', 'edit']);
    });

    Route::middleware(['permission:manage-articles'])->group(function () {
        Route::patch('articles/{article}/status', ArticleStatusController::class);
        Route::resource('articles', ArticleController::class)->except(['create', 'edit']);
    });

    Route::middleware(['permission:manage-favorite-products'])->resource('favorite-products', FavoriteProductController::class)->except(['create', 'edit']);

    Route::middleware(['permission:manage-product-change-requests'])->group(function () {
        Route::post(
            'product-change-requests/{product_change_request}/approve',
            [ProductChangeRequestController::class, 'approve']
        )->name('product-change-requests.approve');
        Route::post(
            'product-change-requests/{product_change_request}/reject',
            [ProductChangeRequestController::class, 'reject']
        )->name('product-change-requests.reject');
        Route::resource('product-change-requests', ProductChangeRequestController::class)->only(
            ['store', 'show', 'index', 'update']
        );
    });

    Route::middleware(['permission:manage-brand-change-requests'])->group(function () {
        Route::post(
            'brand-change-requests/{brand_change_request}/approve',
            [BrandChangeRequestController::class, 'approve']
        )->name('brand-change-requests.approve');
        Route::post(
            'brand-change-requests/{brand_change_request}/reject',
            [BrandChangeRequestController::class, 'reject']
        )->name('brand-change-requests.reject');
        Route::resource('brand-change-requests', BrandChangeRequestController::class)->only(
            ['store', 'show', 'index', 'update']
        );
    });

    Route::prefix('/media-library')->group(function () {
        Route::post('/upload', [MediaController::class, 'upload']);
        Route::post('/upload-multiple', [MediaController::class, 'uploadMultiple']);
        Route::delete('/{media}', [MediaController::class, 'delete']);
        Route::post('/delete-multiple', [MediaController::class, 'deleteMultiple']);
    });
});

Route::prefix('/autocomplete')->group(function () {
    Route::get('/authors', [AutocompleteController::class, 'authors']);
    Route::get('/categories/{type}', [AutocompleteController::class, 'categories']);
    Route::get('/tags', [AutocompleteController::class, 'tags']);

});

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout']);
});
