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
        $statistician=Role::firstOrCreate(['name'=>'Statistician']);
        $statistician->givePermissionTo('View_Patient');
        $statistician->givePermissionTo('View_Case');
        $statistician->givePermissionTo('Reset_User_Password');
        $statistician->givePermissionTo('Reset_Own_Password');

        $deviceManager=Role::firstOrCreate(['name'=>'Device Manager']);
        $deviceManager->givePermissionTo('manage-devices');
        $deviceManager->givePermissionTo('Reset_User_Password');
        $deviceManager->givePermissionTo('Reset_Own_Password');

        //create default user
        $user = User::firstOrCreate([
          'name'=>'Main Data',
          'email'=>'MainData@dynamic.com',
          'password'=>Hash::make('DataAdmin')
        ]);
        $user->assignRole($admin);

        $dataManagerUser = User::firstOrCreate([
          'name'=>'data manager',
          'email'=>'datamanager@dynamic.com',
          'password'=>Hash::make('DataManager')
        ]);
        $dataManagerUser->assignRole($data_manager);

        $statisticianUser = User::firstOrCreate([
          'name'=>'statistician',
          'email'=>'statistician@dynamic.com',
          'password'=>Hash::make('statistician')
        ]);
        $statisticianUser->assignRole($statistician);

        $deviceManagerUser = User::firstOrCreate([
          'name'=>'device manager',
          'email'=>'devicemanager@dynamic.com',
          'password'=>Hash::make('DeviceManager')
        ]);
        $deviceManagerUser->assignRole($deviceManager);
    }
}
