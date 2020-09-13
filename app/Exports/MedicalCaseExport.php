<?php

namespace App\Exports;

use App\MedicalCase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MedicalCaseExport implements FromCollection,WithHeadings,ShouldAutoSize,WithTitle
{
    public function headings():array
    {
      return [
        'Id',
        'version_id',
        'patient_id',
        'created_at',
        'updated_at',
        'local_medical_case_id'
      ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
      return MedicalCase::all();
        // return MedicalCase::with('patient')->get();
        // return collect(MedicalCase::getMedicalCase());
    }

  //   public function map($medicalCase) : array {
  //     return [
  //         $medicalCase->patient->local_patient_id,
  //         Carbon::parse($medicalCase->patient->birthdate)->toFormattedDateString(),
  //         $medicalCase->patient->weight,
  //         $medicalCase->patient->gender,
  //         $medicalCase->local_medical_case_id,
  //         Carbon::parse($medicalCase->created_at)->toFormattedDateString(),
  //         Carbon::parse($medicalCase->updated_at)->toFormattedDateString(),
  //     ] ;
  // }
  public function title():string
  {
    return 'Medical Cases';
  }
}
