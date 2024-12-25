<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\api\TagController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(
    function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::apiResource('/task', TaskController::class);
        Route::apiResource('/category', CategoryController::class)->except('show');
        Route::apiResource('/tag', TagController::class)->except('show');
    }
);
