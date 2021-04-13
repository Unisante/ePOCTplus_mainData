<?php

namespace App\Exports;

use App\Formulation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class FormulationExport implements FromCollection,WithHeadings,ShouldAutoSize,WithEvents,WithTitle
{
  public function headings():array
    {
      return [
        'formulation_id',
        'formulation_medication_form',
        'formulation_administration_route_name',
        'formulation_liquid_concentration',
        'formulation_dose_form',
        'formulation_unique_dose',
        'formulation_by_age',
        'formulation_minimal_dose_per_kg',
        'formulation_maximal_dose',
        'formulation_description',
        'formulation_doses_per_day',
        'formulation_created_at',
        'formulation_updated_at',
        'formulation_drug_id',
        'formulation_administration_route_category'
      ];
    }
    public function registerEvents():array
    {
      return[
        AfterSheet::class => function(AfterSheet $event){
          $event->sheet->getStyle('A1:P1')->applyFromArray([
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
        return Formulation::all();
    }
    public function title():string
    {
      return 'formulations';
    }
}
