<?php

namespace App\Exports;

use App\MedicalCase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MedicalCaseExport implements FromCollection,
WithHeadings,
ShouldAutoSize,
WithTitle,
WithEvents
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
  public function registerEvents():array
  {
    return[
      AfterSheet::class => function(AfterSheet $event){
        $event->sheet->getStyle('A1:F1')->applyFromArray([
          'font'=>[
            'bold'=>true,
          ],

          ]);

        }
      ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
      return MedicalCase::all();
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
