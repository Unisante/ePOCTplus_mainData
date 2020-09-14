<?php

namespace App\Exports;

use App\Node;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class NodeExport implements FromCollection,
WithHeadings,
ShouldAutoSize,
WithTitle,
WithEvents
{
  public function headings():array
    {
      return [
        'Id',
        'medal_c_id',
        'reference',
        'label',
        'type',
        'category',
        'priority',
        'stage',
        'description',
        'formula',
        'answertype_id',
        'algorithm_id',
        'created_at',
        'updated_at',
      ];
    }
    public function registerEvents():array
  {
    return[
      AfterSheet::class => function(AfterSheet $event){
        $event->sheet->getStyle('A1:N1')->applyFromArray([
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
        return Node::all();
    }
    public function title():string
    {
      return 'Nodes';
    }
}
