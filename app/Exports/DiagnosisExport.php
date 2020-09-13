<?php

namespace App\Exports;

use App\Diagnosis;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
class DiagnosisExport implements FromCollection,WithHeadings,ShouldAutoSize,WithTitle
{
  public function headings():array
    {
      return [
        'Id',
        'medal_c_id',
        'label',
        'diagnostic_id',
        'reference',
        'agreed',
        'proposed_additional',
        'created_at',
        'updated_at',
        'medical_case_id',
        'type',
      ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Diagnosis::all();
    }
    public function title():string
    {
      return 'Diagnosis';
    }
}
