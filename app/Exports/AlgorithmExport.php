<?php

namespace App\Exports;

use App\Algorithm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;

class AlgorithmExport implements FromCollection,
WithHeadings,
// ShouldAutoSize,
WithTitle,
WithEvents,
Responsable
{
  use Exportable;
  private $fileName="algorithms.csv";
  public function headings():array
    {
      return [
        'algorithm_id',
        'algorithm_medal_c_id',
        'algorithm_name',
        'algorithm_created_at',
        'algorithm_updated_at',
      ];
    }
    public function registerEvents():array
    {
      return[
        AfterSheet::class => function(AfterSheet $event){
          $event->sheet->getStyle('A1:K1')->applyFromArray([
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
        return Algorithm::all();
    }
    public function title():string
    {
      return 'algorithms';
    }
}