<?php

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;

use Illuminate\Support\Facades\Route;


Route::resource('authors', AuthorController::class)->except(['create', 'edit']);
Route::resource('users', UserController::class)->only(['index', 'show']);
Route::resource('tags', TagController::class)->except(['create', 'edit']);
Route::get('categories/get-types', [CategoryController::class, 'getTypes']);
Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
Route::resource('products', ProductController::class)->except(['create', 'edit']);
Route::get('ingredients/get-ewg-data-types', [IngredientController::class, 'getEwgDataTypes']);
Route::get('ingredients/get-categories', [IngredientController::class, 'getCategories']);
Route::resource('ingredients', IngredientController::class)->except(['create', 'edit']);

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout']);
});
