<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFolderRequest;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * Создание новой папки и сохранение в таблицу.
     *
     * @param  StoreFolderRequest  $request
     * @return JsonResponse
     */
    public function create(StoreFolderRequest $request): JsonResponse
    {
        $user = auth()->user();

        $folder = Folder::create(
            [
                'user_id' => $user->getId(),
                'name' => $request->get('name'),
                'size' => 0
            ]
        );

        return response()->json([
            'data' => $folder
        ]);
    }

    /**
     * Получить все папки пользователя
     *
     * @return JsonResponse
     */
    public function showAll(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $folders = $user::with(['folders', 'folders.files'])->get();;

        return response()->json([
            'data' => $folders
        ]);
    }

    /**
     * Удалить папку
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $folder = Folder::getFolder($user->getId(), $request->id);
        if(!$folder instanceof Folder)
        {
            return response()->json([
                'error' => ['Такой папки не существует']
            ]);
        }

        $nameFolder = $folder->getName();

        //TODO удалить файлы с упоминанием это папки
        $folder->delete();
        $folder->save();

        return response()->json([
            'data' => 'папка'. $nameFolder .'удалена'
        ]);
    }

    /**
     * Получить папку, где указан размер всех файлов внутри
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $folder = Folder::getFolder($user->getId(), $request->id);

        if(!$folder instanceof Folder)
        {
            return response()->json([
                'error' => ['Такой папки не существует']
            ]);
        }

        return response()->json([
            'data' => $folder
        ]);
    }
}
