<?php

namespace App\Exports;

use App\MedicalCaseAnswer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
class MedicalCaseAnswerExport implements FromCollection,WithHeadings,ShouldAutoSize,WithTitle
{
  public function headings():array
    {
      return [
        'Id',
        'medical_case_id',
        'answer_id',
        'node_id',
        'value',
        'created_at',
        'updated_at'
      ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MedicalCaseAnswer::all();
    }
    public function title():string
    {
      return 'Medical Case answers';
    }
}
