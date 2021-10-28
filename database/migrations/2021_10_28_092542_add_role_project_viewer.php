<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddRoleProjectViewer extends Migration
{

    protected function addPermissiontoRole($role_id, $permission){
        $perm = DB::table('permissions')->where('name', '=', $permission)->first();
        if($perm === null){
            $perm_id = DB::table('permissions')->insertGetId(
                array(
                    'name' => $permission,
                    'guard_name' => 'web',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                )
            );
        }else{
            $perm_id = $perm->id;
        }
        DB::table('role_has_permissions')->insert(
            array(
                'permission_id' => $perm_id,
                'role_id' => $role_id
            )
        );
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

        // Add role
        $role_id = DB::table('roles')->insertGetId(
            array(
                'name' => 'Project Viewer',
                'guard_name' => 'web',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            )
        );

        // Add permissions
        $this->addPermissiontoRole($role_id, 'View_Patient');
        $this->addPermissiontoRole($role_id, 'View_Case');
        $this->addPermissiontoRole($role_id, 'Export');
        $this->addPermissiontoRole($role_id, 'Reset_User_Password');
        $this->addPermissiontoRole($role_id, 'Reset_Own_Password');

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

        DB::table('roles')->where('name', '=', 'Project Viewer')->delete();
        DB::table('role_has_permissions')->where('role_id', '=', $role->id)->delete();
    }
}
