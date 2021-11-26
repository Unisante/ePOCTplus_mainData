<?php

namespace App\Jobs;

use App\Services\SaveCaseService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ProcessCaseJson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dir;
    protected $filename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dir, $filename)
    {
        $this->dir = $dir;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $caseData = json_decode(Storage::get("$this->dir/$this->filename"), true);

        //Add offset to timestamps to avoid time zone offsets
        $timeZone = timezone_open(date_default_timezone_get());
        $UTCDateTime = date_create("now", timezone_open("Europe/London"));
        $timeShift = timezone_offset_get($timeZone, $UTCDateTime);
        
        if(isset($caseData['patient']['birth_date'])){
            $caseData['patient']['birth_date'] += $timeShift;
        }

        if ($caseData === null) {
            Log::error("Unable to parse JSON file: $this->filename");
            $this->moveToDir(Config::get('medal.storage.json_failure_dir'));
        }

        try {
            DB::beginTransaction();
            $save = new SaveCaseService;
            $save->save($caseData);
            DB::commit();

            Log::info("Successfully saved case from JSON file: $this->filename");
            $this->moveToDir(Config::get('medal.storage.json_success_dir'));
        }
        catch (Exception $e) {
            DB::rollBack();
            Log::error("Error while attempting to save case from JSON file: $this->filename");
            Log::error("Run `php artisan cases:reload` to attempt to process failing cases");
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            $this->moveToDir(Config::get('medal.storage.json_failure_dir'));
        }
    }

    private function moveToDir($dir) {
        if ($dir != $this->dir) {
            Storage::makeDirectory($dir);
            Storage::move("$this->dir/$this->filename", "$dir/$this->filename");
        }
    }
}
