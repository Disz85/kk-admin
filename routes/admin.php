<?php

use App\Http\Controllers\{AuthorController,UserController};
use Illuminate\Support\Facades\Route;

Route::resource('authors', AuthorController::class)->except(['create', 'edit']);
Route::resource('users', UserController::class)->only(['index','show']);
