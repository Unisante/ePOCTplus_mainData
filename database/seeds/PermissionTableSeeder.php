<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * 'View_X' === Read only
         * 'Manage_X' === Read Write
         */
        $permissions = [
            'Reset_Own_Password',

            'Access_Follow_Up_Panel',
            'Access_Duplicates_Panel',
            'Access_Facilities_Panel',
            'Access_Health_Facilities_Panel',
            'Access_Devices_Panel',
            'Access_Medical_Staff_Panel',
            'Access_Patients_Panel',
            'Access_Medical_Cases_Panel',
            'Access_Diagnoses_Panel',
            'Access_Drugs_Panel',
            'Access_Export_Panel',
            'Access_Profile_Panel',
            'Access_Reset_Own_Password_Panel',
            'Access_Admin_Corner_Panel',
            'Access_ADMIN_PANEL',

            'Create_User',
            'Delete_User',
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
            'See_Sensitive_Data',
            'Export',
            'See_Logs',
        ];

        // Delete permissions
        foreach (Permission::all() as $permission) {
            $permission->delete();
        }

        // Populate database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }
    }
}
