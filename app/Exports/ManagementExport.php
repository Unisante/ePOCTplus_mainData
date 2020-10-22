<?php

namespace App\Exports;

use App\Management;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class ManagementExport implements FromCollection,
WithHeadings,
ShouldAutoSize,
WithTitle,
WithEvents
{
  public function headings():array
    {
      return [
        'Management_Id',
        'medal_c_id',
        'type',
        'reference',
        'label',
        'description',
        'diagnosis_id',
        'custom_diagnosis_id',
        'created_at',
        'updated_at',
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
        return Management::all();
    }
    public function title():string
    {
      return 'Managements';
    }
}
