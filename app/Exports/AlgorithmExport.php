<?php

namespace App\Exports;

use App\Algorithm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
class AlgorithmExport implements FromCollection,WithHeadings,ShouldAutoSize,WithTitle
{
  public function headings():array
    {
      return [
        'id',
        'medal_c_id',
        'name',
        'created_at',
        'updated_at',
      ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Algorithm::all();
    }
    public function title():string
    {
      return 'Algorithms';
    }
}
