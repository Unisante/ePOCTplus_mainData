<?php

namespace App\Console\Commands;

use App\Services\SaveCaseService;
use App\Version;
use Illuminate\Console\Command;

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
    protected $description = 'This command will update the config';

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
            $data = $saveCaseService->getVersionData('', $version->medal_c_id);
            $versionData = $data['medal_r_json'];
            $configData = $saveCaseService->getPatientConfigData('', $version->medal_c_id);
            $version = $saveCaseService->updateVersion($versionData);
            $saveCaseService->updateConfig($configData, $version);
        });
    }
}
