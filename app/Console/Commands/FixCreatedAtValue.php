<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FixCreatedAtValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'json:fix_created {dry-run=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will take every json and get their change the created_at key to createdAt of the patient';

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
        $json_success_files = Storage::files('json_failure');

        foreach ($json_success_files as $json) {
            $filename = array_slice(explode('/', $json), -1)[0];
            $data = json_decode(Storage::get($json, true), true);

            if ($data['patient']['created_at']) {
                $data['patient']['createdAt'] = $data['patient']['created_at'];
                $data['patient']['updatedAt'] = $data['patient']['updated_at'];
                unset(['patient']['created_at']);
                unset(['patient']['updated_at']);
                $newJsonString = json_encode($data);
                file_put_contents(storage_path("app/" . $json), $newJsonString);
                Log::info("File " . $filename . " updated");
                $i++;
            }

        }
        if ($this->argument('dry-run') == 0) {
            $this->info($i . ' patients createdAt key updated');
        } else {
            $this->info($i . ' patients createdAt key would have been updated');
        }
    }
}
