<?php

namespace App\Console\Commands;

use App\Version;
use App\Services\Http;
use Illuminate\Console\Command;
use App\Services\SaveCaseService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class UpdateVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:versions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update the config with the latest version';

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
        $saveCaseService = new SaveCaseService;
        Version::all()->each(function ($version) use ($saveCaseService) {
            $data = Http::get(Config::get('medal.urls.creator_algorithm_url') . $version->medal_c_id);
            $data = json_decode($data['content'], true);
            $versionData = $data['medal_r_json'];
            $configData = $saveCaseService->getPatientConfigData($version->medal_c_id);
            $version = $saveCaseService->updateVersion($versionData);
            $saveCaseService->updateConfig($configData, $version);
        });
    }
}
