<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\reports\AttentionController;

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('user-profile/{name}', [UserController::class, 'userProfile']);

Route::group(['middleware' => ['auth:api']], function() {
    Route::post('refresh-token', [UserController::class, 'refreshToken']);
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('all-user', [UserController::class, 'allUser']);
    Route::post('logout', [UserController::class, 'logout']);
    // ATTENTIONS
    Route::get('table-attention', [AttentionController::class, 'tableAttention']);
    Route::get('form-details', [AttentionController::class, 'formDetails']);
});
