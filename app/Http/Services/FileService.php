<?php

namespace App\Http\Services;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function createFile(User $user, UploadedFile $file, int $folderId = null, string $dateTime = null): File
    {
        DB::beginTransaction();
        try {
            $size = $file->getSize();
            $user->setMaxSizeDisk($user->getMaxSize() - $size);
            $user->save();

            if(!empty($folderId))
            {
                $folder = Folder::find($folderId);
                $folder->size = $folder->size + $size;
                $folder->save();
            }

            $file = File::create(
                [
                    'folder_id'     => $folderId,
                    'user_id'       => $user->getId(),
                    'path'          => $file->store('files'),
                    'original_name' => $file->getClientOriginalName(),
                    'format'        => $file->getClientOriginalExtension(),
                    'size'          => $file->getSize(),
                    'die_datetime'  => $dateTime
                ]
            );
            DB::commit();
        }catch (\Throwable $throwable)
        {
            DB::rollBack();
            throw $throwable;
        }

        return $file;
    }

    public function deleteFile(User $user, File $file): void
    {
        DB::beginTransaction();
        try {
            $size = $file->getSize();
            $user->setMaxSizeDisk($user->getMaxSize() + $size);
            $user->save();

            $folderId = $file->getFolderId();

            if(!empty($folderId))
            {
                $folder = Folder::find($folderId);
                $folder->size = $folder->getSize() - $size;
                $folder->save();
            }

            $path = $file->getPath();
            Storage::delete($path);
            $file->delete();

            DB::commit();
        }catch (\Throwable $throwable)
        {
            DB::rollBack();
            throw $throwable;
        }
    }
}
