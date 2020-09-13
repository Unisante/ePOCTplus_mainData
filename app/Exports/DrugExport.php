<?php

namespace App\Exports;

use App\Drug;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
class DrugExport implements FromCollection,WithHeadings,ShouldAutoSize,WithTitle
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
        'is_anti_malarial',
        'is_antibiotic',
        'formulationSelected',
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
        return Drug::all();
    }
    public function title():string
    {
      return 'Drugs';
    }
}
