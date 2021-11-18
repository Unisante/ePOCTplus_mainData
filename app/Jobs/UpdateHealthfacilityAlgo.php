<?php

namespace App\Jobs;

use App\HealthFacility;
use App\Services\AlgorithmService;
use App\Services\Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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
        try {
            $url = Config::get('medal.creator.url') .
                Config::get('medal.creator.versions_endpoint') .
                "/" . $this->healthFacility->healthFacilityAccess->creator_version_id;

            $urlAlgorithm = Config::get('medal.creator.url') .
                Config::get('medal.creator.algorithms_endpoint') .
                "/" . $this->healthFacility->healthFacilityAccess->medal_c_algorithm_id .
                "/emergency_content";

            $version = json_decode(Http::get($url, []),true);
            $emergencyContent = json_decode(Http::post($urlAlgorithm, [],
                                                      ["emergency_content_version" => -1]),
                                             true);

            $this->algorithmService->assignVersion($this->healthFacility, $version, $emergencyContent);
            Log::info("Algorithm and emergency content updated for  HF ID : " . $this->healthFacility->id);
        } catch (\Exception $exception) {
            Log::error("Error when updating algorithm and emergency content of " . $this->healthFacility->id .
                " | detail : " . $exception);
        }

    }
}
