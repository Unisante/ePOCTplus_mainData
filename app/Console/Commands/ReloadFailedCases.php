<?php

namespace App\Console\Commands;

use App\Jobs\ProcessCaseJson;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReloadFailedCases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cases:reload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $failedFiles = Storage::files(env('JSON_FAILURE_DIR'));
        $fileCount = count($failedFiles);
        if ($fileCount == 0) {
            $this->info("No cases to reload");
            return;
        }

        $this->info("Reloading $fileCount files...");
        foreach($failedFiles as $failedJson) {
            $filename = array_slice(explode('/', $failedJson), -1)[0];
            ProcessCaseJson::dispatch(env('JSON_FAILURE_DIR'), $filename);
        }
        Log::info($fileCount . " failed case(s) reloaded.");
    }
}
