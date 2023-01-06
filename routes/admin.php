<?php

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;

use Illuminate\Support\Facades\Route;

Route::resource('authors', AuthorController::class)->except(['create', 'edit']);
Route::resource('users', UserController::class)->only(['index','show']);
Route::resource('tags', TagController::class)->except(['create', 'edit']);
Route::get('categories/get-types', [CategoryController::class, 'getTypes']);
Route::resource('categories', CategoryController::class)->except(['create', 'edit']);

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout']);
});
