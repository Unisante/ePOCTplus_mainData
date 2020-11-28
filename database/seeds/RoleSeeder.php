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
        // create Data manager
        $data_manager=Role::firstOrCreate(['name'=>'Data Manager']);
        $data_manager->givePermissionTo('View_Patient');
        $data_manager->givePermissionTo('View_Case');
        $data_manager->givePermissionTo('Edit_Patient');
        $data_manager->givePermissionTo('Edit_Case');
        $data_manager->givePermissionTo('Merge_Duplicates');
        $data_manager->givePermissionTo('Delete_Patient');
        $data_manager->givePermissionTo('Delete_Case');
        $data_manager->givePermissionTo('Reset_User_Password');
        $data_manager->givePermissionTo('Reset_Own_Password');
        // create statictician
        $statictician=Role::firstOrCreate(['name'=>'Statictician']);
        $data_manager->givePermissionTo('View_Patient');
        $data_manager->givePermissionTo('View_Case');
        $data_manager->givePermissionTo('Reset_User_Password');
        $data_manager->givePermissionTo('Reset_Own_Password');
        //create default user
        $user = User::firstOrCreate([
          'name'=>'Main Data',
          'email'=>'MainData@dynamic.com',
          'password'=>Hash::make('DataAdmin')
        ]);
        $user->assignRole($admin);
    }
}
