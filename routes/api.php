<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
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

});
