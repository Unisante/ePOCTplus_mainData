<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ProcessUploadZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $extractDir = env('JSON_EXTRACT_DIR');
        Storage::makeDirectory($extractDir);

        $zip = new ZipArchive;
        $zip->open(Storage::disk('local')->path($this->path));

        for ($i = 0; $i < $zip->count(); $i++) {
            $jsonName = Str::uuid() . '.json';
            $zip->renameIndex($i, $jsonName);
            $zip->extractTo(Storage::disk('local')->path($extractDir), $jsonName);
            ProcessCaseJson::dispatch($jsonName);
        }

        $zip->close();
    }
}
