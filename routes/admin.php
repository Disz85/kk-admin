<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::resource('authors', AuthorController::class)->except(['create', 'edit']);
Route::resource('users', UserController::class)->only(['index','show']);
Route::resource('tags', TagController::class)->except(['create', 'edit']);

Route::prefix('/auth')->group(function () {
    Route::get('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
