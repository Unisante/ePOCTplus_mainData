<?php

namespace App\Jobs;

use App\HealthFacility;
use App\Services\AlgorithmService;
use App\Services\Http;
use App\VersionJson;
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
            Log::info("Update HF : " .$this->healthFacility->id);
            /*
             * Récupération de l'algorithm
             *      - envoi du json_version pour optenir la dernier version.
             *      - Si on est à jours on reçoit un 204 si non un 200 avec le json
             */
            $versionJson = VersionJson::where('health_facility_id', $this->healthFacility->id)->first();
            if ($versionJson == null) {
                Log::error("can't update HF without version_jsons entry");
                return null;
            }

            $url = Config::get('medal.creator.url') . Config::get('medal.creator.versions_endpoint') .
                "/" . $this->healthFacility->healthFacilityAccess->creator_version_id;
            $version = Http::get($url,
                ["json_version" => $this->healthFacility->healthFacilityAccess->medal_r_json_version]);

            switch ($version['code']) {
                case "200" :
                    $versionDecoded = json_decode($version['content'], true);
                    $this->algorithmService->updateVersion($versionJson,
                        $versionDecoded);
                    $healthFacilityAccess = $this->healthFacility->healthFacilityAccess;
                    $this->algorithmService->updateHealthFacilityAccessJsonVersion($healthFacilityAccess,
                        $versionDecoded['medal_r_json_version']);
                    Log::info("Algorithm updated");
                    break;

                case "204" :
                    Log::info("Algorithm is up-to-date");
                    break ;

                default :
                    Log::error("HTTP error code" . $version['code'] . "not expected");
                    break;
            }

            /*
             * Récupération du emergency content lié à l'algorithm
             *      - envoi du emergency_content_version pour optenir la dernier version.
             *      - Si on est à jours on reçoit un 204 si non un 200 avec le json
             */
            $urlAlgorithm = Config::get('medal.creator.url') .
                Config::get('medal.creator.algorithms_endpoint') .
                "/" . $this->healthFacility->healthFacilityAccess->medal_c_algorithm_id . "/emergency_content";
            $emergencyContent =Http::post($urlAlgorithm, [],
                ["emergency_content_version" => $versionJson->emergency_content_version]);

            switch ($emergencyContent['code']) {
                case "200" :
                    $this->algorithmService->updateVersionEmergencyContent($versionJson,
                        json_decode($emergencyContent['content'], true));
                        Log::info("Emergency content updated");
                    break;
                case "204" :
                    Log::info("Emergency_content is up-to-date");
                    break ;
                default :
                    Log::error("HTTP error code" . $version['code'] . "not expected");
                    break;
            }
        } catch (\Exception $exception) {
            Log::error("Error when updating algorithm and emergency content");
            Log::error($exception);
        }

    }
}
