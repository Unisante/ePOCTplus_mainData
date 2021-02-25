<?php

namespace App\Exports;


use App\AdditionalDrug;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AdditionalDrugExport implements FromCollection,
WithHeadings,
ShouldAutoSize,
WithTitle,
WithEvents
{

  public function headings():array
  {
    return [
      'a_drug_id',
      'a_drug_reference_drug_id',
      'a_drug_medical_case_id',
      'a_drug_formulationSelected',
      'a_drug_agreed',
      'a_drug_version_id',
      'a_drug_created_at',
      'a_drug_updated_at'
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
      return AdditionalDrug::all();
  }
  public function title():string
  {
    return 'additional_drugs';
  }
}
