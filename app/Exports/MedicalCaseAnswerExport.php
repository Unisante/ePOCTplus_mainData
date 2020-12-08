<?php

namespace App\Exports;

use App\MedicalCaseAnswer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class MedicalCaseAnswerExport implements FromCollection,
WithHeadings,
ShouldAutoSize,
WithTitle,
WithEvents
{
  public function headings():array
    {
      return [
        'medical_case_answer_id',
        'medical_case_answer_medical_case_id',
        'medical_case_answer_answer_id',
        'medical_case_answer_node_id',
        'medical_case_answer_value',
        'medical_case_answer_created_at',
        'medical_case_answer_updated_at'
      ];
    }
    public function registerEvents():array
  {
    return[
      AfterSheet::class => function(AfterSheet $event){
        $event->sheet->getStyle('A1:G1')->applyFromArray([
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
        return MedicalCaseAnswer::all();
    }
    public function title():string
    {
      return 'medical_case_answers';
    }
}
