<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//| API Routes
Route::apiResource('/categories', CategoryController::class);
Route::apiResource('/news', NewsController::class);

Route::get('newsWithCategory', [NewsController::class, 'newsWithCategory']);
Route::get('/news/category/{id}', [NewsController::class, 'newsByCategory']);