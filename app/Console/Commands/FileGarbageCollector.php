<?php

namespace App\Console\Commands;

use App\Http\Services\FileService;
use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

class FileGarbageCollector extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:collect-garbage-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Удаление истекших срока хранения файлов
     *
     * @throws Throwable
     */
    public function handle(FileService $fileService)
    {
        $files = File::get();
        $nowDate = Carbon::now();

        /** @var File $file */
        foreach ($files as $file)
        {
            $fileDieDatetime = $file->getDieDatetime();
            if($fileDieDatetime !== null)
            {
                if($nowDate > $fileDieDatetime)
                {
                    $user = User::find($file->getUserId());
                    $fileService->deleteFile($user, $file);
                }
            }
        }
    }
}
