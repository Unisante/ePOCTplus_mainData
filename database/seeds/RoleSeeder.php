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
<<<<<<< HEAD
=======
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
        $statistician = Role::firstOrCreate(['name'=>'Statistician']);
        $statistician->givePermissionTo('View_Patient');
        $statistician->givePermissionTo('View_Case');
        $statistician->givePermissionTo('Reset_User_Password');
        $statistician->givePermissionTo('Reset_Own_Password');

        $logistician = Role::firstOrCreate(['name'=>'Logistician']);
        $logistician->givePermissionTo('Manage_Health_Facilities');
        $logistician->givePermissionTo('Manage_Devices');
        $logistician->givePermissionTo('Reset_User_Password');
        $logistician->givePermissionTo('Reset_Own_Password');

        //create default user
        $user = User::firstOrCreate([
          'name'=>'admin',
          'email'=>'admin@dynamic.com',
          'password'=>Hash::make('1234')
        ]);

        $user->assignRole($admin);

        $dataManagerUser = User::firstOrCreate([
          'name'=>'data manager',
          'email'=>'datamanager@dynamic.com',
          'password'=>Hash::make('1234')
        ]);
        $dataManagerUser->assignRole($data_manager);

        $statisticianUser = User::firstOrCreate([
          'name'=>'statistician',
          'email'=>'statistician@dynamic.com',
          'password'=>Hash::make('1234')
        ]);
        $statisticianUser->assignRole($statistician);

        $logisticianUser = User::firstOrCreate([
          'name'=>'logistician',
          'email'=>'logistician@dynamic.com',
          'password'=>Hash::make('1234')
        ]);
        $logisticianUser->assignRole($logistician);

        $user = User::firstOrCreate([
          'name' => 'user',
          'email' => 'user@dynamic.com',
          'password' => Hash::make('1234')
        ]);
>>>>>>> parent of a89f0db (removed every roles except admin)
    }
}
