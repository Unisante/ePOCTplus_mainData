<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLogisticianRole extends Migration
{
    private function addPermissionToRole($permission_name, $role_id){
        $permission = DB::table('permissions')->where('name', '=', $permission_name)->first();
        if($permission === null){
            $permission_id = DB::table('permissions')->insertGetId([
                'name' => $permission_name,
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }else{
            $permission_id = $permission->id;
        }


        DB::table('role_has_permissions')->insert([
            'permission_id' => $permission_id,
            'role_id' => $role_id
        ]);
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $logistician = DB::table('roles')->where('name', '=', 'Logistician')->first();
        if($logistician !== null){
            return;
        }

        # Add logistician if it does not exist
        $logistician_id = DB::table('roles')->insertGetId([
            'name' => 'Logistician',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        # Add permissions
        $this->addPermissionToRole('Manage_Health_Facilities', $logistician_id);
        $this->addPermissionToRole('Manage_Devices', $logistician_id);
        $this->addPermissionToRole('Manage_Medical_Staff', $logistician_id);
        $this->addPermissionToRole('Reset_User_Password', $logistician_id);
        $this->addPermissionToRole('Reset_Own_Password', $logistician_id);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $logistician = DB::table('roles')->where('name', '=', 'Logistician')->first();
        if($logistician === null){
            return;
        }

        # Remove logistician if it does exist
        DB::table('roles')->where('name', '=', 'Logistician')->delete();

        # Remove permissions
        DB::table('role_has_permissions')->where('role_id', '=', $logistician->id)->delete();
    }
}
