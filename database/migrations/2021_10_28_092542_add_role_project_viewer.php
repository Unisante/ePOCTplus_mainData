<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddRoleProjectViewer extends Migration
{

    /**
     * Insert a permission to the database.
     */
    protected function addPermissionGetId($permission){
        return DB::table('permissions')->insertGetId(
            array(
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            )
        );
    }

    /**
     * Adds a permission to a role given its id.
     */
    protected function addPermissiontoRole($role_id, $permission){
        $perm = DB::table('permissions')->where('name', '=', $permission)->first();
        $perm_id = ($perm === null) ? $this->addPermissionGetId($permission) : $perm->id;
        DB::table('role_has_permissions')->insert(
            array(
                'permission_id' => $perm_id,
                'role_id' => $role_id
            )
        );
    }

    /**
     * Removes a permission to a role given its id.
     */
    protected function removePermissiontoRole($role_id, $permission){
        $perm = DB::table('permissions')->where('name', '=', $permission)->first();
        if($perm !== null){
            DB::table('role_has_permissions')
                ->where('permission_id', '=', $perm->id)
                ->where('role_id', '=', $role_id)
                ->delete();
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(DB::table('roles')->where('name', '=', 'Project Viewer')->first() !== null){
            return;
        }

        // Add Project Viewer role
        $role_id = DB::table('roles')->insertGetId(
            array(
                'name' => 'Project Viewer',
                'guard_name' => 'web',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            )
        );

        // Add permissions to Project Viewer
        $this->addPermissiontoRole($role_id, 'View_Patient');
        $this->addPermissiontoRole($role_id, 'View_Case');
        $this->addPermissiontoRole($role_id, 'Export');
        $this->addPermissiontoRole($role_id, 'Reset_User_Password');
        $this->addPermissiontoRole($role_id, 'Reset_Own_Password');

        // Add See_Sensitive_Data permission
        $this->addPermissionGetId('See_Sensitive_Data');

        // Add See_Sensitive_Data to default roles
        $admin = DB::table('roles')->where('name', '=', 'Administrator')->first();
        if($admin !== null){
            $this->addPermissiontoRole($admin->id, 'See_Sensitive_Data');
        }

        $data_manager = DB::table('roles')->where('name', '=', 'Data Manager')->first();
        if($data_manager !== null){
            $this->addPermissiontoRole($data_manager->id, 'See_Sensitive_Data');
        }

        $statistician = DB::table('roles')->where('name', '=', 'Statistician')->first();
        if($statistician !== null){
            $this->addPermissiontoRole($statistician->id, 'See_Sensitive_Data');
        }

        $logistician = DB::table('roles')->where('name', '=', 'Logistician')->first();
        if($logistician !== null){
            $this->addPermissiontoRole($logistician->id, 'See_Sensitive_Data');
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = DB::table('roles')->where('name', '=', 'Project Viewer')->first();
        if(DB::table('roles')->where('name', '=', 'Project Viewer')->first() === null){
            return;
        }

        // Remove Project Viewer role
        DB::table('roles')->where('name', '=', 'Project Viewer')->delete();

        // Remove permissions related to Project Viewer
        DB::table('role_has_permissions')->where('role_id', '=', $role->id)->delete();

        // Remove See_Sensitive_Data permission
        DB::table('permissions')->where('name', '=', 'See_Sensitive_Data')->delete();

        // Remove See_Sensitive_Data to default roles
        $admin = DB::table('roles')->where('name', '=', 'Administrator')->first();
        if($admin !== null){
            $this->removePermissiontoRole($admin->id, 'See_Sensitive_Data');
        }

        $data_manager = DB::table('roles')->where('name', '=', 'Data Manager')->first();
        if($data_manager !== null){
            $this->removePermissiontoRole($data_manager->id, 'See_Sensitive_Data');
        }

        $statistician = DB::table('roles')->where('name', '=', 'Statistician')->first();
        if($statistician !== null){
            $this->removePermissiontoRole($statistician->id, 'See_Sensitive_Data');
        }

        $logistician = DB::table('roles')->where('name', '=', 'Logistician')->first();
        if($logistician !== null){
            $this->removePermissiontoRole($logistician->id, 'See_Sensitive_Data');
        }
    }
}
