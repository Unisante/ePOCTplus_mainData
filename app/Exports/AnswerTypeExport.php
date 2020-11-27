<?php

namespace App\Exports;

use App\AnswerType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AnswerTypeExport implements FromCollection,
WithHeadings,
ShouldAutoSize,
WithTitle,
WithEvents
{
  public function headings():array
    {
      return [
        'answer_type_id',
        'value',
        'created_at',
        'updated_at',
      ];
    }
    public function registerEvents():array
    {
      return[
        AfterSheet::class => function(AfterSheet $event){
          $event->sheet->getStyle('A1:D1')->applyFromArray([
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
        return AnswerType::all();
    }
    public function title():string
    {
      return 'answer_type';
    }
}
