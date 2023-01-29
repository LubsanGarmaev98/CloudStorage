<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteFileRequest;
use App\Http\Requests\DownloadFileRequest;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Services\FileService;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Js;
use Symfony\Component\HttpFoundation\StreamedResponse;


class FileController extends Controller
{
    /**
     * Создание нового файла и сохранение в таблицу.
     *
     * @param StoreFileRequest $request
     * @param FileService $fileService
     * @return JsonResponse
     * @throws \Throwable
     */
    public function create(StoreFileRequest $request, FileService $fileService): JsonResponse
    {
        /** @var User $user */
        $user        = auth()->user();
        $file        = $request->file('file');
        $folderId    = $request->get('folderId');
        $dieDatetime = $request->get('die_datetime');

        $createdFile = $fileService->createFile($user, $file, $folderId, $dieDatetime);

        return response()->json([
            'data' => $createdFile
        ]);
    }

    /**
     * Получить все файлы пользователя.
     *
     * @return JsonResponse
     */
    public function showAll(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        return response()->json([
            'data' => $user::with(['files', 'files.folder'])->get()
        ]);
    }

    /**
     * Скачать указанный файл.
     *
     * @param Request $request
     * @return StreamedResponse|JsonResponse
     */
    public function download(Request $request): StreamedResponse|JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $file = File::getFile($user->getId(), $request->id);
        if(!$file instanceof File)
        {
            return response()->json([
                'error' => 'Файл не найден, файла с таким id не существует!', 404
            ]);
        }

        return Storage::download($file->getPath(), $file->getName());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateFileRequest  $request
     * @return JsonResponse
     */
    public function update(UpdateFileRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $file = File::getFile($user->getId(), $request->id);

        if(!$file instanceof File)
        {
            return response()->json([
                'error' => 'Файл не найден, файла с таким id не существует!', 404
            ]);
        }

        $file->setOriginalName($request->get('original_name'));
        $file->save();

        return response()->json([
            'date' => $file
        ]);
    }

    /**
     * Удалить файл
     *
     * @param FileService $fileService
     * @param  Request  $request
     * @return JsonResponse
     */
    public function delete(Request $request, FileService $fileService): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $file = File::getFile($user->getId(), $request->id);
        if(!$file instanceof File)
        {
            return response()->json([
                'error' => 'Файл не найден, файла с таким id не существует!', 404
            ]);
        }

        $fileService->deleteFile($user, $file);

        return response()->json([
            'message' => 'Файл успешно удален!'
        ]);
    }

    /**
     * Создание публичной ссылки на файл
     *
     * @param  Request $request
     * @return string
     */
    public function getPublicLink(Request $request): string
    {
        /** @var User $user */
        $user = auth()->user();
        $file = File::getFile($user->getId(), $request->id);
        return Storage::url($file->getPath());
    }
}
