<?php

namespace App\Exports;

use App\Answer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
class AnswerExport implements FromCollection,
WithTitle,
ShouldAutoSize,
WithHeadings,
WithEvents
{
  public function headings():array
  {
    return [
      'Id',
      'medal_c_id',
      'node_id',
      'created_at',
      'updated_at',
      'label',
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
    return Answer::all();
  }
  public function title():string
  {
    return 'Answers';
  }
}
