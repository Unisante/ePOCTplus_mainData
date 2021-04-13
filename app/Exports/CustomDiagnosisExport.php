<?php

namespace App\Exports;


use App\Custom_diagnosis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomDiagnosisExport implements FromCollection,
WithHeadings,
// ShouldAutoSize,
WithTitle,
WithEvents
{
  public function headings():array
  {
    return [
      'custom_diagnosis_id',
      'custom_diagnosis_label',
      'custom_diagnosis_drugs',
      'custom_diagnosis_created_at',
      'custom_diagnosis_updated_at',
      'custom_diagnosis_medical_case_id',
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
    return Custom_diagnosis::all();
  }
  public function title():string
  {
    return 'custom_diagnoses';
  }
}
