<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create admin role
        $admin=Role::firstOrCreate(['name'=>'Administrator']);
        $admin->givePermissionTo('Access_ADMIN_PANEL');
        $admin->givePermissionTo('Create_User');
        $admin->givePermissionTo('Delete_User');
        $admin->givePermissionTo('Reset_User_Password');
        $admin->givePermissionTo('Reset_Own_Password');
        $admin->givePermissionTo('View_Audit_Trail');
        $admin->givePermissionTo('See_Sensitive_Data');

        // create data manager role
        $data_manager=Role::firstOrCreate(['name'=>'Data Manager']);
        $data_manager->givePermissionTo('View_Patient');
        $data_manager->givePermissionTo('View_Case');
        $data_manager->givePermissionTo('Edit_Patient');
        $data_manager->givePermissionTo('Edit_Case');
        $data_manager->givePermissionTo('Merge_Duplicates');
        $data_manager->givePermissionTo('Export');
        $data_manager->givePermissionTo('Delete_Patient');
        $data_manager->givePermissionTo('Delete_Case');
        $data_manager->givePermissionTo('Reset_User_Password');
        $data_manager->givePermissionTo('Reset_Own_Password');
        $data_manager->givePermissionTo('See_Sensitive_Data');

        // create project viewer role
        $project_viewer=Role::firstOrCreate(['name'=>'Project Viewer']);
        $project_viewer->givePermissionTo('View_Patient');
        $project_viewer->givePermissionTo('View_Case');
        $project_viewer->givePermissionTo('Edit_Patient');
        $project_viewer->givePermissionTo('Edit_Case');
        $project_viewer->givePermissionTo('Export');
        $project_viewer->givePermissionTo('Delete_Patient');
        $project_viewer->givePermissionTo('Delete_Case');
        $project_viewer->givePermissionTo('Reset_User_Password');
        $project_viewer->givePermissionTo('Reset_Own_Password');

        // create statictician role
        $statistician = Role::firstOrCreate(['name'=>'Statistician']);
        $statistician->givePermissionTo('View_Patient');
        $statistician->givePermissionTo('View_Case');
        $statistician->givePermissionTo('Reset_User_Password');
        $statistician->givePermissionTo('Reset_Own_Password');
        $statistician->givePermissionTo('See_Sensitive_Data');

        // create logistician role
        $logistician = Role::firstOrCreate(['name'=>'Logistician']);
        $logistician->givePermissionTo('Manage_Health_Facilities');
        $logistician->givePermissionTo('Manage_Devices');
        $logistician->givePermissionTo('Reset_User_Password');
        $logistician->givePermissionTo('Reset_Own_Password');
        $logistician->givePermissionTo('See_Sensitive_Data');
    }
}
