<?php

namespace App\Exports;

use App\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PatientExport implements FromCollection,WithHeadings
{
  public function headings():array
    {
      return [
        'Id',
        'first_name',
        'last_name',
        'created_at',
        'updated_at',
        'birthdate',
        'weight',
        'gender',
        'local_patient_id'
      ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Patient::all();
    }
}
