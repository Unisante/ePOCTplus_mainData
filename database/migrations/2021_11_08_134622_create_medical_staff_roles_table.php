<?php

use App\MedicalStaffRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMedicalStaffRolesTable extends Migration
{
    private function insertMedicalStaffRole($label, $alias){
        MedicalStaffRole::create([
            'label' => $label,
            'alias' => $alias
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
            $table->string('label');
            $table->string('alias')->nullable();
            $table->timestamps();
        });

        $this->insertMedicalStaffRole('Medical Doctor', 'MD');
        $this->insertMedicalStaffRole('Assistant Medical Officer', 'AMO');
        $this->insertMedicalStaffRole('Clinical Officer', 'CO');
        $this->insertMedicalStaffRole('Nurse', 'NU');
        $this->insertMedicalStaffRole('Pharmacist', 'PH');
        $this->insertMedicalStaffRole('Midwife', 'MW');
        $this->insertMedicalStaffRole('Registration Assistant', 'RA');


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
