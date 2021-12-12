<?php

namespace App\Jobs;

use App\MedicalCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\RedCapApiService;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Exceptions\RedCapApiServiceException;
use IU\PHPCap\PhpCapException;
use IU\PHPCap\RedCapProject;
use Illuminate\Support\Facades\Config;

class RemoveFollowUp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var MedicalCase $medicalCase */
    protected $medicalCase;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($medicalCase)
    {
        $this->medicalCase = $medicalCase;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $project = new RedCapProject(Config::get('redcap.identifiers.api_url_followup'), Config::get('redcap.identifiers.api_token_followup'));
        $case_id=(array)$this->medicalCase->local_medical_case_id;
        try {
            $project->deleteRecords($case_id);
            Log::info("Case Id '{$case_id[0]}' Has been removed from Redcap Folloup");
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
        $this->medicalCase->duplicate = 1;
        $this->medicalCase->save();
    }
}
