<?php

namespace App\Exports;

use App\Version;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VersionExport implements FromCollection,WithHeadings,ShouldAutoSize,WithTitle
{
  public function headings():array
  {
    return [
      'Id',
      'medal_c_id',
      'name',
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
        return Version::all();
    }
    public function title():string
    {
      return 'Versions';
    }
}
