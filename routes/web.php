<?php

use App\Http\Controllers\CategoryController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', [CategoryController::class, 'index']);
Route::get('/categories-search', [CategoryController::class, 'search'])->name('categories.search');
Route::resource('categories', CategoryController::class);
Route::get('categories/deactivate/{category}', [CategoryController::class, 'deactivate'])->name('categories.deactivate');
