<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\UserController;
use App\Rules\UserFileExists;
use Illuminate\Http\Request;
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
    // Регистрация пользователя
    Route::post('signup', [UserController::class, 'signup']);
    // Аутентификация пользователя
    Route::post('login', [AuthController::class, 'login']);
});

Route::group([
    'middleware' => 'jwt.auth',
], function ($router) {
    // Выйти из системы
    Route::post('logout', [AuthController::class, 'logout']);
    // Обновить jwt-токен
    Route::post('refresh', [AuthController::class, 'refresh']);
    // Получить аутентифицированного пользователя
    Route::post('me', [AuthController::class, 'me']);

    // Редактировать данные пользователя
    Route::put('users', [UserController::class, 'update']);
    // Удалить пользователя
    Route::delete('users', [UserController::class, 'delete']);

    // Загрузка файла
    Route::post('files', [FileController::class, 'create']);
    // Переименовать файл
    Route::put('files/{id}', [FileController::class, 'update']);
    // Удалить файл
    Route::delete('files/{id}', [FileController::class, 'delete']);
    // Получить список файлов
    Route::get('files', [FileController::class, 'showAll']);
    // Скачать файл
    Route::get('files/{id}', [FileController::class, 'download']);

    //Создание папки
    Route::post('folders', [FolderController::class, 'create']);
    // Получить список папок
    Route::get('folders', [FolderController::class, 'showAll']);
    //Удалить папку
    Route::delete('folders/{id}', [FolderController::class, 'delete']);

    //Получить размер файлов внутри директории
    Route::get('folders/{id}', [FolderController::class, 'get']);

    //Получить размер всех файлов на диске
    Route::get('users/current-files-size', [UserController::class, 'getCurrentFilesSize']);

    //Сгенерировать уникальную публичную ссылку
    Route::get('files/{id}/public-link', [FileController::class, 'getPublicLink']);
});

