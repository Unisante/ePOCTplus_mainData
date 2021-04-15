<?php

namespace App\Exports;

use App\Diagnosis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class DiagnosisExport implements FromCollection,
WithHeadings,
// ShouldAutoSize,
WithTitle,
WithEvents
{
  public function headings():array
    {
      return [
        'diagnosis_id',
        'diagnosis_medal_c_id',
        'diagnosis_label',
        'diagnosis_diagnostic_id',
        'diagnosis_created_at',
        'diagnosis_updated_at',
        'diagnosis_type',
        'diagnosis_version_id',
      ];
    }
    public function registerEvents():array
    {
      return[
        AfterSheet::class => function(AfterSheet $event){
          $event->sheet->getStyle('A1:H1')->applyFromArray([
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
        return Diagnosis::all();
    }
    public function title():string
    {
      return 'diagnosis';
    }
}
