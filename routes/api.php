<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register',[AuthController::class, 'register']);
Route::post('login',[AuthController::class, 'login']);

Route::middleware('web')->group(function(){
    Route::get('google', [AuthController::class,'google']);
    Route::get('google/callback',[AuthController::class,'googleCallback']);
    Route::get('google/passed',[AuthController::class, 'test']);
});

Route::middleware(['auth:api'])->group(function(){
    Route::get('test',[AuthController::class, 'test']);
});
