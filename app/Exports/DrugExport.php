<?php

namespace App\Exports;

use App\Drug;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class DrugExport implements FromCollection,
WithHeadings,
ShouldAutoSize,
WithTitle,
WithEvents
{
  public function headings():array
    {
      return [
        'drug_id',
        'medal_c_id',
        'type',
        'label',
        'description',
        'diagnosis_id',
        'created_at',
        'updated_at',
        'is_anti_malarial',
        'is_antibiotic'
      ];
    }
    public function registerEvents():array
    {
      return[
        AfterSheet::class => function(AfterSheet $event){
          $event->sheet->getStyle('A1:J1')->applyFromArray([
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
        return Drug::all();
    }
    public function title():string
    {
      return 'drugs';
    }
}
