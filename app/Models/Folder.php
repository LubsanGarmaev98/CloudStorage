<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'size'
    ];

    public static function getFolder(int $userId, int $folderId): ?Folder
    {
        $folder = Folder::select("*")
                ->where('id', $folderId)
                ->where('user_id', $userId)
                ->first();

        return $folder;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
