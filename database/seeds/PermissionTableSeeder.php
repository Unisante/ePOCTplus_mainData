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
      $permissions = [
        'Access_ADMIN_PANEL',
        'Create_User',
        'Delete_User',
        'Reset_User_Password',
        'Reset_Own_Password',
        'View_Patient',
        'View_Case',
        'Edit_Patient',
        'Edit_Case',
        'Merge_Duplicates',
        'Delete_Patient',
        'Delete_Case',
        'View_Audit_Trail',
        'Manage_Health_Facilities',
        'Manage_Devices',
     ];

     foreach($permissions as $permission){
       Permission::firstOrCreate([
         'name'=>$permission,
       ]);
     }
    }
}
