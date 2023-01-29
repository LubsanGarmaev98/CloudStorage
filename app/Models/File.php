<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'user_id',
        'path',
        'original_name',
        'format',
        'size',
        'die_datetime'
    ];

    public function folder()
    {
        return $this->hasOne(Folder::class, 'id', 'folder_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function getFile(int $userId, int $fileId): File|null
    {
        $file = File::select("*")
                    ->where('id', $fileId)
                    ->where('user_id', $userId)
                    ->first();

        return $file;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setOriginalName(string $originalName)
    {
        $this->original_name = $originalName;
    }

    public function getName(): string
    {
        return $this->original_name;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getFolderId(): ?int
    {
        return $this->folder_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getDieDatetime(): ?Carbon
    {
        if (empty($this->die_datetime))
        {
            return null;
        }
        return new Carbon($this->die_datetime);
    }
}
