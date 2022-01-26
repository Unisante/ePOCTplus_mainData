<?php

use App\MedicalStaffRole;
use Illuminate\Database\Seeder;

class MedicalStaffRoleSeeder extends Seeder
{

    private function insertMedicalStaffRole($type, $label)
    {
        MedicalStaffRole::firstOrCreate([
            'type' => $type,
            'label' => $label,
        ]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertMedicalStaffRole('medical_doctor', 'Medical Doctor (MD)');
        $this->insertMedicalStaffRole('assistant_medical_officer', 'Assistant Medical Officer (AMO)');
        $this->insertMedicalStaffRole('clinical_officer', 'Clinical Officer (CO)');
        $this->insertMedicalStaffRole('nurse', 'Nurse');
        $this->insertMedicalStaffRole('pharmacist', 'Pharmacist');
        $this->insertMedicalStaffRole('midwife', 'Midwife');
        $this->insertMedicalStaffRole('registration_assistant', 'Registration Assistant');
    }
}
