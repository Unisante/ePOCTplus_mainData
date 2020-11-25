<?php

namespace App\Exports;

use App\ManagementReference;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ManagementReferenceExport implements FromCollection,
WithHeadings,
WithTitle,
ShouldAutoSize,
WithEvents
{
  public function headings():array{
    return [
      'management_reference_id',
      'agreed',
      'diagnosis_id',
      'created_at',
      'updated_at',
      'management_id',
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
  public function collection(){
      return ManagementReference::all();
  }
  public function title():string
    {
      return 'management_reference';
    }
}
