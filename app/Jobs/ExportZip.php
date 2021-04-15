<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Excel;
use App\Patient;
use App\Exports\PatientExport;
use App\Exports\DataSheet;
use App\Exports\DiagnosisReferenceExport;
use App\Exports\AnswerExport;
use App\Exports\Medical_CaseExport;
use App\Exports\MedicalCaseAnswerExport;
use App\Exports\DrugReferenceExport;
use App\Exports\AlgorithmExport;
use App\Exports\AdditionalDrugExport;
use App\Exports\CustomDiagnosisExport;
use App\Exports\ManagementReferenceExport;
use App\Exports\DrugExport;
use App\Exports\DiagnosisExport;
use App\Exports\FormulationExport;
use App\Exports\ManagementExport;
use App\Exports\NodeExport;
use App\Exports\AnswerTypeExport;
use App\Exports\VersionExport;
use Carbon;

class ExportZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user_email;
    protected $tempFiles;
    protected $tempZip;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_email,$tempFiles,$tempZip)
    {
      $this->user_email=$user_email;
      $this->tempFiles=$tempFiles;
      $this->tempZip=$tempZip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      // dd($this->user_email_date);
        Excel::store(new PatientExport, 'tempExcels/'.$this->user_email.'/patients.csv');
        Excel::store(new Medical_CaseExport, 'tempExcels/'.$this->user_email.'/medical_cases.csv');
        Excel::store(new MedicalCaseAnswerExport, 'tempExcels/'.$this->user_email.'/medical_case_answers.csv');
        Excel::store(new AnswerExport, 'tempExcels/'.$this->user_email.'/answers.csv');
        Excel::store(new DiagnosisReferenceExport, 'tempExcels/'.$this->user_email.'/diagnosis_references.csv');
        Excel::store(new CustomDiagnosisExport, 'tempExcels/'.$this->user_email.'/custom_diagnoses.csv');
        Excel::store(new DrugReferenceExport, 'tempExcels/'.$this->user_email.'/drug_references.csv');
        Excel::store(new AdditionalDrugExport, 'tempExcels/'.$this->user_email.'/additional_drugs.csv');
        Excel::store(new ManagementReferenceExport, 'tempExcels/'.$this->user_email.'/management_references.csv');
        Excel::store(new DiagnosisExport, 'tempExcels/'.$this->user_email.'/diagnosis.csv');
        Excel::store(new DrugExport, 'tempExcels/'.$this->user_email.'/drugs.csv');
        Excel::store(new FormulationExport, 'tempExcels/'.$this->user_email.'/formulations.csv');
        Excel::store(new ManagementExport, 'tempExcels/'.$this->user_email.'/managements.csv');
        Excel::store(new NodeExport, 'tempExcels/'.$this->user_email.'/nodes.csv');
        Excel::store(new AnswerTypeExport, 'tempExcels/'.$this->user_email.'/answer_types.csv');
        Excel::store(new AlgorithmExport, 'tempExcels/'.$this->user_email.'/algorithms.csv');
        Excel::store(new VersionExport, 'tempExcels/'.$this->user_email.'/versions.csv');
        $zipper = new \Madnest\Madzipper\Madzipper;
        $zipper->make($this->tempZip)->add($this->tempFiles)->close();
    }
}
