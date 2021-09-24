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
  // ShouldAutoSize,
  WithTitle,
  WithEvents
{

  public function headings():array
  {
    return [
      'patient_id',
      'patient_local_patient_id',
      'patient_first_name',
      'patient_last_name',
      'patient_birthdate',
      'patient_weight',
      'patient_gender',
      'patient_group_id',
      'patient_consent',
      'patient_other_uid',
      'merged',
      'merged_with',
      'related_ids',
      'patient_created_at',
      'patient_updated_at',
    ];
  }
  public function registerEvents():array
  {
    return[
      AfterSheet::class => function(AfterSheet $event){
        $event->sheet->getStyle('A1:O1')->applyFromArray([
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
    return Patient::select('id','local_patient_id','first_name','last_name','birthdate','weight','gender','group_id','consent','other_uid','merged','merged_with','related_ids','created_at','updated_at')->get();
  }
  public function title():string
  {
    return 'patients';
  }
}
