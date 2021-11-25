<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Illuminate\Support\Facades\Hash;

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
        $admin->givePermissionTo('Manage_Health_Facilities');
        $admin->givePermissionTo('Manage_Devices');
        $admin->givePermissionTo('Manage_Medical_Staff');

        // create data manager role
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

        // create statictician role
        $statistician=Role::firstOrCreate(['name'=>'Statistician']);
        $data_manager->givePermissionTo('See_Sensitive_Data');

        // create project viewer role
        $project_viewer=Role::firstOrCreate(['name'=>'Project Viewer']);
        $project_viewer->givePermissionTo('View_Patient');
        $project_viewer->givePermissionTo('View_Case');
        $project_viewer->givePermissionTo('Export');
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
        $logistician=Role::firstOrCreate(['name'=>'Logistician']);
        $logistician->givePermissionTo('Manage_Health_Facilities');
        $logistician->givePermissionTo('Manage_Devices');
        $logistician->givePermissionTo('Manage_Medical_Staff');
        $logistician->givePermissionTo('Reset_User_Password');
        $logistician->givePermissionTo('Reset_Own_Password');

        // create admin user
        $admin_user = User::firstOrCreate([
          'name'=>'Admin',
          'email'=>'admin@dynamic.com',
          'password'=>Hash::make('admin')
        ]);
        $admin_user->assignRole($admin);

        // create data manager user
        $data_manager_user = User::firstOrCreate([
          'name'=>'Data Manager',
          'email'=>'datamanager@dynamic.com',
          'password'=>Hash::make('datamanager')
        ]);
        $data_manager_user->assignRole($data_manager);

        // create statistician user
        $statistician_user = User::firstOrCreate([
          'name'=>'statistician',
          'email'=>'statistician@dynamic.com',
          'password'=>Hash::make('statistician')
        ]);
        $statistician_user->assignRole($statistician);

        // create logistician role
        $logistician = Role::firstOrCreate(['name'=>'Logistician']);
        $logistician->givePermissionTo('Manage_Health_Facilities');
        $logistician->givePermissionTo('Manage_Devices');
        $logistician->givePermissionTo('Reset_User_Password');
        $logistician->givePermissionTo('Reset_Own_Password');
        $logistician->givePermissionTo('See_Sensitive_Data');
        // create logistician user
        $logistician_user = User::firstOrCreate([
          'name'=>'logistician',
          'email'=>'logistician@dynamic.com',
          'password'=>Hash::make('logistician')
        ]);
        $logistician_user->assignRole($logistician);
    }
}
