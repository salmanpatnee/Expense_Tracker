<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

Route::get('/expenses/totals', [ExpenseController::class, 'totals']);
Route::apiResource('expenses', ExpenseController::class)->only(['index', 'store', 'destroy']);
