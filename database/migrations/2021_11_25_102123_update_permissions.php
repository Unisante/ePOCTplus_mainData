<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class UpdatePermissions extends Migration
{

    private static function addPermissionToUser($permission_name, $role){
        $permission = DB::table('permissions')->where('name', '=', $permission_name)->first();
        if(!$permission){
            return;
        }

        DB::table('role_has_permissions')->insert([
            'permission_id' => $permission->id,
            'role_id' => $role->id
        ]);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # Delete all data from permission table
        DB::table('permissions')->truncate();
        DB::table('role_has_permissions')->truncate();


        # Add new permissions
        $permissions = [
            'Access_Follow_Up_Panel',
            'Access_Duplicates_Panel',
            'Access_Facilities_Panel',
            'Access_Health_Facilities_Panel',
            'Access_Devices_Panel',
            'Access_Patients_Panel',
            'Access_Medical_Cases_Panel',
            'Access_Diagnoses_Panel',
            'Access_Drugs_Panel',
            'Access_Export_Panel',
            'Access_Profile_Panel',
            'Access_Reset_Own_Password_Panel',
            'Access_Admin_Corner_Panel',
    
            'Reset_Own_Password',

            'Manage_Roles',
            'Manage_Users',
            'View_Patients',
            'Manage_Patients',
            'Manage_Patients_Merge_Duplicates',
            'View_Medical_Cases',
            'Manage_Medical_Cases',
            'View_Follow_Ups',
            'View_Questions',
            'View_Health_Facilities',
            'Manage_Health_Facilities',
            'Manage_Devices',
            'View_Devices',
            'Export',
    
            'See_Sensitive_Data'
         ];

        foreach($permissions as $permission){
            DB::table('permissions')->insert([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        # Add permissions to roles
        // create admin role
        $admin=Role::firstOrCreate(['name'=>'Administrator']);
        self::addPermissionToUser('Access_Admin_Corner_Panel', $admin);
        self::addPermissionToUser('Access_Reset_Own_Password_Panel', $admin);
        self::addPermissionToUser('Access_Profile_Panel', $admin);

        // create data manager role
        $data_manager=Role::firstOrCreate(['name'=>'Data Manager']);
        self::addPermissionToUser('Access_Reset_Own_Password_Panel', $data_manager);
        self::addPermissionToUser('Access_Profile_Panel', $data_manager);
        self::addPermissionToUser('Access_Export_Panel', $data_manager);
        self::addPermissionToUser('Access_Drugs_Panel', $data_manager);
        self::addPermissionToUser('Access_Diagnoses_Panel', $data_manager);
        self::addPermissionToUser('Access_Medical_Cases_Panel', $data_manager);
        self::addPermissionToUser('Access_Patients_Panel', $data_manager);
        self::addPermissionToUser('Access_Devices_Panel', $data_manager);
        self::addPermissionToUser('Access_Health_Facilities_Panel', $data_manager);
        self::addPermissionToUser('Access_Facilities_Panel', $data_manager);
        self::addPermissionToUser('Access_Duplicates_Panel', $data_manager);
        self::addPermissionToUser('Access_Follow_Up_Panel', $data_manager);
        self::addPermissionToUser('Manage_Patients', $data_manager);
        self::addPermissionToUser('Manage_Patients_Merge_Duplicates', $data_manager);
        self::addPermissionToUser('Manage_Medical_Cases', $data_manager);
        self::addPermissionToUser('View_Follow_Ups', $data_manager);
        self::addPermissionToUser('View_Questions', $data_manager);
        self::addPermissionToUser('Manage_Health_Facilities', $data_manager);
        self::addPermissionToUser('Manage_Devices', $data_manager);
        self::addPermissionToUser('Export', $data_manager);
        self::addPermissionToUser('See_Sensitive_Data', $data_manager);

        // create project viewer role
        $project_viewer=Role::firstOrCreate(['name'=>'Project Viewer']);
        self::addPermissionToUser('Access_Reset_Own_Password_Panel', $project_viewer);
        self::addPermissionToUser('Access_Profile_Panel', $project_viewer);
        self::addPermissionToUser('Access_Medical_Cases_Panel', $project_viewer);
        self::addPermissionToUser('Access_Patients_Panel', $project_viewer);
        self::addPermissionToUser('View_Patients', $project_viewer);
        self::addPermissionToUser('View_Medical_Cases', $project_viewer);
        self::addPermissionToUser('View_Follow_Ups', $project_viewer);
        self::addPermissionToUser('View_Questions', $project_viewer);
        self::addPermissionToUser('View_Health_Facilities', $project_viewer);
        self::addPermissionToUser('View_Devices', $project_viewer);

        // create statictician role
        $statistician = Role::firstOrCreate(['name'=>'Statistician']);
        self::addPermissionToUser('Access_Reset_Own_Password_Panel', $statistician);
        self::addPermissionToUser('Access_Profile_Panel', $statistician);
        self::addPermissionToUser('Access_Medical_Cases_Panel', $statistician);
        self::addPermissionToUser('Access_Patients_Panel', $statistician);
        self::addPermissionToUser('View_Patients', $statistician);
        self::addPermissionToUser('View_Medical_Cases', $statistician);

        // create logistician role
        $logistician = Role::firstOrCreate(['name'=>'Logistician']);
        self::addPermissionToUser('Access_Reset_Own_Password_Panel', $logistician);
        self::addPermissionToUser('Access_Profile_Panel', $logistician);
        self::addPermissionToUser('Access_Devices_Panel', $logistician);
        self::addPermissionToUser('Access_Health_Facilities_Panel', $logistician);
        self::addPermissionToUser('Access_Facilities_Panel', $logistician);
        self::addPermissionToUser('Manage_Health_Facilities', $logistician);
        self::addPermissionToUser('Manage_Devices', $logistician);
        self::addPermissionToUser('Export', $logistician);
        self::addPermissionToUser('See_Sensitive_Data', $logistician);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
