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
// ShouldAutoSize,
WithTitle,
WithEvents
{
  public function headings():array
    {
      return [
        'node_id',
        'node_medal_c_id',
        'node_reference',
        'node_label',
        'node_type',
        'node_category',
        'node_priority',
        'node_stage',
        'node_description',
        'node_formula',
        'node_answertype_id',
        'node_algorithm_id',
        'node_created_at',
        'node_updated_at',
        'node_is_identifiable'
      ];
    }
    public function registerEvents():array
  {
    return[
      AfterSheet::class => function(AfterSheet $event){
        $event->sheet->getStyle('A1:O1')->applyFromArray([
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
      return 'nodes';
    }
}
