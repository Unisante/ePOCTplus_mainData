<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessCaseJson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filename;
    protected $extractPath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        $extractDir = env('JSON_EXTRACT_DIR');
        $this->extractPath = "$extractDir/$this->filename";
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $caseData = json_decode(Storage::get($this->extractPath), true);
        if ($caseData === null) {
            Log::error("Unable to parse JSON file: $this->filename");
            $this->moveToDir(env('JSON_FAILURE_DIR'));
        }
        
        try {
            // TODO save the case

            Log::info("Successfully saved case from JSON file: $this->filename");
            $this->moveToDir(env('JSON_SUCCESS_DIR'));
        } catch (\Throwable $th) {
            Log::error("Error while attempting to save case from JSON file: $this->filename");
            $this->moveToDir(env('JSON_FAILURE_DIR'));
        }
    }

    private function moveToDir($dir) {
        Storage::makeDirectory($dir);
        Storage::move($this->extractPath, "$dir/$this->filename");
    }
}
