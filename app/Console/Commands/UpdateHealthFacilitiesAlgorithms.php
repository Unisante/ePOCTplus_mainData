<?php

namespace App\Console\Commands;

use App\HealthFacility;
use App\Jobs\UpdateHealthfacilityAlgo;
use App\Services\AlgorithmService;
use Illuminate\Console\Command;

class UpdateHealthFacilitiesAlgorithms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'HealthFacilitiesAlgo:update';

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
        HealthFacility::all()->each(function (HealthFacility $healFacility) {
            dispatch(new UpdateHealthfacilityAlgo($healFacility));
        });
    }
}
