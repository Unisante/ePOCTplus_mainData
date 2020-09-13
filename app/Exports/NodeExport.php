<?php

namespace App\Exports;

use App\Node;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
class NodeExport implements FromCollection,WithHeadings,ShouldAutoSize,WithTitle
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
