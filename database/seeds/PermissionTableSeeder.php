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
        'View Admin Panel',
        'Change Password',
        'View Patients',
        'View Medicals',
        'View Duplicates',
        'Merge Duplicates',
        'Delete Records',
        'Delete Users',
        'Edit Users',
        'Create Users',
        'Assign Roles',
        'Access Grafana(c)',
        'Access Grafana(e)'
     ];

     foreach($permissions as $permission){
       Permission::create([
         'name'=>$permission,
       ]);
     }
    }
}
