<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// POSTMAN URL:   

Route::post('signup',[AuthController::class, 'signup']);
Route::post('login',[AuthController::class, 'login']);

// for single route middleware
//Route::post('logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function(){
 Route::post('logout',[AuthController::class, 'logout']);
 Route::apiResource('post',PostController::class);

});
