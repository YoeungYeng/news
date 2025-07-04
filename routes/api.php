<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsController;
use App\Models\Category;

// Login Route (no auth required)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (JWT required)
Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('news', NewsController::class);
});


Route::get('newsWithCategory', [NewsController::class, 'newsWithCategory']);
Route::get('/news/category/{id}', [NewsController::class, 'newsByCategory']);
// all category
Route::get('/getcategory', [CategoryController::class, 'getCategory']);