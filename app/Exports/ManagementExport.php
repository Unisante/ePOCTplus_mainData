<?php

namespace App\Exports;

use App\Management;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
class ManagementExport implements FromCollection,WithHeadings,ShouldAutoSize,WithTitle
{
  public function headings():array
    {
      return [
        'Id',
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
