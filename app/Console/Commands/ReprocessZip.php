<?php

namespace App\Console\Commands;

use ZipArchive;
use App\Jobs\ProcessCaseJson;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ReprocessZip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zip:reprocess {dry-run=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will take every zip file and reprocess them if they are not already in the json_success folder';

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
        $total = 0;
        if ($this->argument('dry-run') == 1) {
            $this->info('Dry Run');
        }


        $zips = Storage::files(Config::get('medal.storage.cases_zip_dir'));
        $extractDir = Config::get('medal.storage.json_extract_dir');
        Storage::makeDirectory($extractDir);

        foreach ($zips as $zip) {
            $current_zip = new ZipArchive;
            $res = $current_zip->open(Storage::disk('local')->path($zip));
            if ($res === true) {
                for ($i = 0; $i < $current_zip->numFiles; $i++) {
                    $jsonName = $current_zip->getNameIndex($i);
                    if (!Storage::disk('local')->exists('json_success/' . $jsonName)) {
                        if (!Storage::disk('local')->exists('json_failure/' . $jsonName)) {
                            if ($this->argument('dry-run') == 0) {
                                $current_zip->extractTo(Storage::disk('local')->path($extractDir), $jsonName);
                                ProcessCaseJson::dispatch($extractDir, $jsonName);
                            }
                            $total++;
                        }
                    }
                }
                $current_zip->close();
            }
        }

        if ($this->argument('dry-run') == 0) {
            $this->info($total . ' json have been reprocessed');
        } else {
            $this->info($total . ' json would have been reprocessed');
        }
    }
}
