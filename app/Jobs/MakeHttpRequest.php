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

class MakeHttpRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $algorithmService;
    protected $healthFacility;
    protected $chosenAlgorithmID;
    protected $versionID;

    public function __construct(HealthFacility $healthFacility, $chosenAlgorithmID, $versionID)
    {
        $this->healthFacility = $healthFacility;
        $this->chosenAlgorithmID = $chosenAlgorithmID;
        $this->versionID = $versionID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AlgorithmService $algorithmService)
    {
        /*
         * Récupération de l'algorithm
         *  - envoi du json_version pour optenir la dernier version.
         *  - Si on est à jours on reçoit un 204 si non un 200 avec le json
         *  - Si on a pas d'algorithm en DB alors on passe -1 pour obtenir la dernier version
         */
        $url = Config::get('medal.creator.url') . Config::get('medal.creator.versions_endpoint') .
        "/" . $this->versionID;
        $version = Http::get($url, ["json_version" => optional($this->healthFacility->healthFacilityAccess)->medal_r_json_version ?? -1]);
        $version = json_decode($version['content'], true);

        /*
         * Récupération du emergency content lié à l'algorithm
         *  - envoi du emergency_content_version pour optenir la dernier version.
         *  - Si on est à jours on reçoit un 204 si non un 200 avec le json
         *  - Si on a pas de emergency_content en DB alors on passe -1 pour obtenir la dernier version
         */
        $urlAlgorithm = Config::get('medal.creator.url') . Config::get('medal.creator.algorithms_endpoint') .
        "/" . $this->chosenAlgorithmID . "/emergency_content";

        $emergencyContent = Http::post($urlAlgorithm, [],
            ["emergency_content_version" => -1]);
        $emergencyContent = json_decode($emergencyContent['content'], true);

        $algorithmService->assignVersion($this->healthFacility, $version);
        $algorithmService->assignEmergencyContent($this->healthFacility, $emergencyContent);
        $algorithmService->updateAccesses($this->healthFacility, $this->chosenAlgorithmID, $version);
        Log::info("Assignation done");
    }
}
