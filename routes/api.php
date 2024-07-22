<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\reports\AttentionController;
use App\Http\Controllers\reports\StatusController;
use App\Http\Controllers\Administrator\UsersController;
use App\Http\Controllers\Repository\DocumentController;
use App\Http\Controllers\External\PersonalController;

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('user-profile/{name}', [UserController::class, 'userProfile']);

//PERSONAL
Route::get('combo', [PersonalController::class, 'combo']);
Route::post('validar', [PersonalController::class, 'validar']);
Route::get('formdata/{num_doc}', [PersonalController::class, 'formdata']);
Route::post('storeform', [PersonalController::class, 'storeform']);


//RECURSOS
Route::get('entity/{idcentro_mac}', [PersonalController::class, 'entity']);
Route::get('provincias/{departamento_id}', [PersonalController::class, 'provincias']);
Route::get('distritos/{provincia_id}', [PersonalController::class, 'distritos']);

Route::group(['middleware' => ['auth:api']], function() {
    Route::post('refresh-token', [UserController::class, 'refreshToken']);
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('all-user', [UserController::class, 'allUser']);
    Route::post('logout', [UserController::class, 'logout']);

    Route::get('form-details', [AttentionController::class, 'formDetails']);
    // ATTENTIONS
    Route::get('table-attention', [AttentionController::class, 'tableAttention']);
    Route::get('table-status', [StatusController::class, 'tableStatus']);

    // ADMINISTRATION
    Route::get('users-list', [UsersController::class, 'usersList']);
    Route::post('users-add', [UsersController::class, 'usersAdd']);
    Route::get('users-details', [UsersController::class, 'usersDetails']);

    //REPOSITORIO
    Route::get('repository-treeview', [DocumentController::class, 'repositoryTreeview']);
    Route::get('repository-list', [DocumentController::class, 'repositoryList']);
    Route::post('repository-store-doc', [DocumentController::class, 'repositoryStoreDoc']);
    Route::post('repository-update-doc/{id}', [DocumentController::class, 'repositoryUpdate']);
    Route::post('repository-delete-doc', [DocumentController::class, 'repositoryDelete']);
});
