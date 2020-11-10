<?php

namespace App\Exports;
use DB;
use App\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class PatientExport implements
  FromCollection,
  WithHeadings,
  ShouldAutoSize,
  WithTitle,
  WithEvents
{

  public function headings():array
  {
    return [
      'patient_id',
      'local_patient_id',
      'first_name',
      'last_name',
      'birthdate',
      'weight',
      'gender',
      'group_id',
      'created_at',
      'updated_at',
    ];
  }
  public function registerEvents():array
  {
    return[
      AfterSheet::class => function(AfterSheet $event){
        $event->sheet->getStyle('A1:J1')->applyFromArray([
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
    return Patient::select('id','local_patient_id','first_name','last_name','birthdate','weight','gender','group_id','created_at','updated_at')->get();
  }
  public function title():string
  {
    return 'patients';
  }
}
