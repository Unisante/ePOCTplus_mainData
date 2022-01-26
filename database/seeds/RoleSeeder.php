<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete all role data
        foreach (Role::all() as $role) {
            $role->delete();
        }

        // Delete all role's permissions
        foreach (DB::table('role_has_permissions')->get() as $role_has_permission) {
            $role_has_permission->delete();
        }

        // create admin role
        $this->createUser('Administrator', [
            'Access_Admin_Corner_Panel',
            'Access_Reset_Own_Password_Panel',
            'Access_Profile_Panel',
            'See_Logs',
            'Manage_Roles',
            'Access_ADMIN_PANEL',
            'Create_User',
            'Delete_User',
            // Admin has by default all other rights.
        ]);

        // create data manager role
        $this->createUser('Data Manager', [
            'Access_Reset_Own_Password_Panel',
            'Access_Profile_Panel',
            'Access_Medical_Cases_Panel',
            'Access_Patients_Panel',
            'Access_Export_Panel',
            'Access_Drugs_Panel',
            'Access_Diagnoses_Panel',
            'Access_Health_Facilities_Panel',
            'Access_Facilities_Panel',
            'Access_Devices_Panel',
            'Access_Medical_Staff_Panel',
            'Access_Duplicates_Panel',
            'Access_Follow_Up_Panel',

            'Reset_Own_Password',

            'Manage_Patients',
            'Manage_Patients_Merge_Duplicates',
            'Manage_Medical_Cases',
            'View_Follow_Ups',
            'View_Questions',
            'Manage_Health_Facilities',
            'Manage_Medical_Staff',
            'Manage_Devices',

            'Export',
            'See_Sensitive_Data',
        ]);

        // create project viewer role
        $this->createUser('Project Viewer', [
            'Access_Reset_Own_Password_Panel',
            'Access_Profile_Panel',
            'Access_Medical_Cases_Panel',
            'Access_Patients_Panel',
            'Access_Export_Panel',

            'Reset_Own_Password',

            'View_Patients',
            'View_Medical_Cases',
            'View_Follow_Ups',
            'View_Questions',
            'View_Health_Facilities',
            'View_Medical_Staff',
            'View_Devices',

            'Export',
        ]);

        // create statistician role
        $this->createUser('Statistician', [
            'Access_Reset_Own_Password_Panel',
            'Access_Profile_Panel',
            'Access_Medical_Cases_Panel',
            'Access_Patients_Panel',
            'Access_Export_Panel',

            'Reset_Own_Password',

            'View_Patients',
            'View_Medical_Cases',

            'Export',
            'See_Sensitive_Data',
        ]);

        // create logistician role
        $this->createUser('Logistician', [
            'Access_Reset_Own_Password_Panel',
            'Access_Profile_Panel',
            'Access_Health_Facilities_Panel',
            'Access_Facilities_Panel',
            'Access_Devices_Panel',
            'Access_Medical_Staff_Panel',
            'Access_Export_Panel',

            'Reset_Own_Password',

            'Manage_Health_Facilities',
            'Manage_Devices',
            'Manage_Medical_Staff',

            'Export',
            'See_Sensitive_Data',
        ]);
    }

    private function addPermissionToRole($permission_name, $role_id)
    {
        $permission = DB::table('permissions')->where('name', '=', $permission_name)->first();
        if ($permission === null) {
            $permission_id = DB::table('permissions')->insertGetId([
                'name' => $permission_name,
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $permission_id = $permission->id;
        }

        DB::table('role_has_permissions')->insert([
            'permission_id' => $permission_id,
            'role_id' => $role_id,
        ]);
    }

    private function createUser($name, $permission_names)
    {
        $role = DB::table('roles')->where('name', '=', $name)->first();
        if ($role !== null) {
            return;
        }

        # Add role if it does not exist
        $user_id = DB::table('roles')->insertGetId([
            'name' => $name,
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        # Add permissions
        foreach ($permission_names as $permission_name) {
            $this->addPermissionToRole($permission_name, $user_id);
        }
        $this->command->info('Created role ' . $name . '.');
    }
}
