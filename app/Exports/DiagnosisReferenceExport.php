<?php

namespace App\Exports;

use App\DiagnosisReference;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DiagnosisReferenceExport implements FromCollection,
WithHeadings,
WithTitle,
// ShouldAutoSize,
WithEvents
{
  public function headings():array{
      return [
        'diagnosis_reference_id',
        'diagnosis_reference_agreed',
        'diagnosis_reference_proposed_additional',
        'diagnosis_reference_diagnosis_id',
        'diagnosis_reference_medical_case_id',
        'diagnosis_reference_created_at',
        'diagnosis_reference_updated_at',
      ];
  }
  public function registerEvents():array
    {
      return[
        AfterSheet::class => function(AfterSheet $event){
          $event->sheet->getStyle('A1:G1')->applyFromArray([
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
  public function collection(){
    return DiagnosisReference::all();
  }
  public function title():string
    {
      return 'diagnosis_reference';
    }
}
