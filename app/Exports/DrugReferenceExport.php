<?php

namespace App\Exports;

use App\DrugReference;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DrugReferenceExport implements FromCollection,
WithHeadings,
WithTitle,
ShouldAutoSize,
WithEvents
{
  public function headings():array{
    return [
      'drugReference_id',
      'drug_id',
      'diagnosis_id',
      'agreed',
      'created_at',
      'updated_at',
      'formulationSelected',
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
    public function collection()
    {
        return DrugReference::all();
    }

    public function title():string
    {
      return 'drug_reference';
    }
}
