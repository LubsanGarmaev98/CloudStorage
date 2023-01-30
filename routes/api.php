<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'guest',
], function ($router) {
    Route::post('signup', [UserController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::group([
    'middleware' => 'jwt.auth',
], function ($router) {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

    Route::put('users', [UserController::class, 'update']);
    Route::delete('users', [UserController::class, 'delete']);

    Route::post('files', [FileController::class, 'create']);
    Route::put('files/{id}', [FileController::class, 'update']);
    Route::delete('files/{id}', [FileController::class, 'delete']);
    Route::get('files', [FileController::class, 'showAll']);
    Route::get('files/{id}', [FileController::class, 'download']);

    Route::post('folders', [FolderController::class, 'create']);
    Route::get('folders', [FolderController::class, 'showAll']);
    Route::delete('folders/{id}', [FolderController::class, 'delete']);

    Route::get('folders/{id}', [FolderController::class, 'get']);

    Route::get('users/current-files-size', [UserController::class, 'getCurrentFilesSize']);

    Route::get('files/{id}/public-link', [FileController::class, 'getPublicLink']);
});

