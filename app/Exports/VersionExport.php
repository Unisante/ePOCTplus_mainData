<?php

namespace App\Exports;

use App\Version;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class VersionExport implements FromCollection,
WithHeadings,
// ShouldAutoSize,
WithTitle,
WithEvents
{
  public function headings():array
  {
    return [
      'version_id',
      'medal_c_id',
      'name',
      'algorithm_id',
      'created_at',
      'updated_at',
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
        return Version::all();
    }
    public function title():string
    {
      return 'versions';
    }
}