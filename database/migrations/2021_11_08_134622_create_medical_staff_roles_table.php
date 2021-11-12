<?php

use App\MedicalStaffRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMedicalStaffRolesTable extends Migration
{
    private function insertMedicalStaffRole($type, $label){
        MedicalStaffRole::create([
            'type' => $type,
            'label' => $label
        ]);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_staff_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('label');
            $table->timestamps();
        });

        $this->insertMedicalStaffRole('medical_doctor', 'Medical Doctor (MD)');
        $this->insertMedicalStaffRole('assistant_medical_officer', 'Assistant Medical Officer (AMO)');
        $this->insertMedicalStaffRole('clinical_officer', 'Clinical Officer (CO)');
        $this->insertMedicalStaffRole('nurse', 'Nurse');
        $this->insertMedicalStaffRole('pharmacist', 'Pharmacist');
        $this->insertMedicalStaffRole('midwife', 'Midwife');
        $this->insertMedicalStaffRole('registration_assistant', 'Registration Assistant');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_staff_roles');
    }
}
