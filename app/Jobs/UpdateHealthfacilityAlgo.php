<?php

namespace App\Jobs;

use App\HealthFacility;
use App\Services\AlgorithmService;
use App\Services\HealthFacilityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateHealthfacilityAlgo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var HealthFacility */
    protected $healthFacility;

    /** @var AlgorithmService */
    protected $algorithmService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(HealthFacility $healthFacility)
    {
        $this->healthFacility = $healthFacility;
        $this->algorithmService =  new AlgorithmService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->algorithmService->assignVersionToHealthFacility(
            $this->healthFacility,
            $this->healthFacility->healthFacilityAccess->creator_version_id)
        ;
        // TODO : update emergency content

    }
}
