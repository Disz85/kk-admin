<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\IngredientController;
use App\Http\Controllers\Api\ProductChangeRequestController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::patch('product-change-requests/store-patch', [ ProductChangeRequestController::class, 'storePatch' ])
        ->name('product-change-requests.store-patch');
    Route::resource('product-change-requests', ProductChangeRequestController::class)
        ->except(['create','edit']);
});

Route::middleware(['cache.headers:max_age=' . config('api.max-age')])->group(function () {
    Route::get('/articles', [ ArticleController::class, 'index' ])
        ->name('articles.index');
    Route::get('/articles/{article:uuid}', [ ArticleController::class, 'show' ])
        ->name('articles.show');

    Route::get('/categories', [ CategoryController::class, 'index' ])
        ->name('categories.index');
    Route::get('/categories/{category:uuid}', [ CategoryController::class, 'show' ])
        ->name('categories.show');

    Route::get('/brands/{brand:uuid}', [ BrandController::class, 'show' ])
        ->name('brands.show');
    Route::get('/brands', [ BrandController::class, 'index' ])
        ->name('brands.index');

    Route::get('/ingredients', [ IngredientController::class, 'index' ])
        ->name('ingredients.index');
    Route::get('/ingredients/{ingredient:slug}', [ IngredientController::class, 'show' ])
        ->name('ingredients.show');

    Route::get('products', [ ProductController::class, 'index' ])
        ->name('products.index');
    Route::get('products/{product:slug}', [ ProductController::class, 'show' ])
        ->name('products.show');
    Route::get('products/ingredient-functions/{product:slug}', [ ProductController::class, 'show' ])
        ->name('products.ingredientFunctions');
});
