<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ArticleStatusController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BrandChangeRequestController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductChangeRequestController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('authors', AuthorController::class)->except(['create', 'edit']);
Route::resource('users', UserController::class)->only(['index', 'show']);
Route::resource('tags', TagController::class)->except(['create', 'edit']);
Route::get('categories/get-types', [CategoryController::class, 'getTypes']);
Route::resource('brands', BrandController::class)->except(['create', 'edit']);
Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
Route::resource('products', ProductController::class)->except(['create', 'edit']);
Route::get('ingredients/get-ewg-data-types', [IngredientController::class, 'getEwgDataTypes']);
Route::get('ingredients/get-categories', [IngredientController::class, 'getCategories']);
Route::resource('ingredients', IngredientController::class)->except(['create', 'edit']);
Route::patch('articles/{article}/status', ArticleStatusController::class);
Route::resource('articles', ArticleController::class)->except(['create', 'edit']);

Route::post('product-change-requests/{product_change_request}/approve', [ProductChangeRequestController::class, 'approve'])->name('product-change-requests.approve');
Route::post('product-change-requests/{product_change_request}/reject', [ProductChangeRequestController::class, 'reject'])->name('product-change-requests.reject');
Route::resource('product-change-requests', ProductChangeRequestController::class)->only(['store','show','index','update']);

Route::post('brand-change-requests/{brand_change_request}/approve', [BrandChangeRequestController::class, 'approve'])->name('brand-change-requests.approve');
Route::post('brand-change-requests/{brand_change_request}/reject', [BrandChangeRequestController::class, 'reject'])->name('brand-change-requests.reject');
Route::resource('brand-change-requests', BrandChangeRequestController::class)->only(['store','show','index','update']);

Route::prefix('/media-library')->group(function () {
    Route::post('/upload', [MediaController::class, 'upload']);
    Route::post('/upload-multiple', [MediaController::class, 'uploadMultiple']);
    Route::delete('/{media}', [MediaController::class, 'delete']);
    Route::post('/delete-multiple', [MediaController::class, 'deleteMultiple']);
});

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout']);
});
