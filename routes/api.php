<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::post('login', [Controllers\UserController::class, 'login']);
Route::post('register', [Controllers\UserController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', [Controllers\UserController::class, 'details']);
    Route::post('user', [Controllers\UserController::class, 'edit']);

    Route::get('tags', [Controllers\TagController::class, 'all']);

    Route::get('articles', [Controllers\ArticleController::class, 'all']);
    Route::get('articles/{id}', [Controllers\ArticleController::class, 'single']);

    Route::get('order', [Controllers\OrderController::class, 'getOrder']);
    Route::post('order', [Controllers\OrderController::class, 'makeOrder']);

    Route::get('ratings', [Controllers\OrderController::class, 'getRatings']);
    Route::post('ratings', [Controllers\OrderController::class, 'postRatings']);
});
