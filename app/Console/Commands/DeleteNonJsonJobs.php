<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteNonJsonJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:delete_non_json {dry-run=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will delete every jobs that are not json process';

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
        if ($this->argument('dry-run') == 1) {
            $this->info('Dry Run');
        }
        $i = 0;
        DB::table('jobs')->latest('id')->chunk(50, function ($jobs) use (&$i) {
            foreach ($jobs as $job) {
                $payload = json_decode($job->payload, true);
                if (array_key_exists('displayName', $payload) && strpos($payload['displayName'], 'ProcessCaseJson') !== false) {
                    if ($this->argument('dry-run') == 0) {
                        $current_job = DB::table('jobs')->find($job->id);
                        $current_job->delete();
                    }
                    $i++;
                }
            }
        });

        if ($this->argument('dry-run') == 0) {
            $this->info($i . ' jobs deleted');
        } else {
            $this->info($i . ' jobs would have been deleted');
        }
    }
}
