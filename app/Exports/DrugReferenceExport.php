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
// ShouldAutoSize,
WithEvents
{
  public function headings():array{
    return [
      'drug_reference_id',
      'drug_reference_drug_id',
      'drug_reference_diagnosis_id',
      'drug_reference_agreed',
      'drug_reference_created_at',
      'drug_reference_updated_at',
      'drug_reference_formulation_selected',
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
        return DrugReference::all()->chunk(200);
    }

    public function title():string
    {
      return 'drug_reference';
    }
}
