<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsToRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      foreach(Role::all() as $role){
        if($role->name=='admin'){
          $role->givePermissionTo('View Admin Panel');
          $role->givePermissionTo('Change Password');
        }elseif($role->name=='data_manager'){
          $role->givePermissionTo('Change Password');
        }elseif($role->name=='clinivisor_user'){
          $role->givePermissionTo('Change Password');
          $role->givePermissionTo('Access Grafana(c)');

        }elseif($role->name=='e_mergence_User'){
          $role->givePermissionTo('Change Password');
          $role->givePermissionTo('Access Grafana(c)');
        }else{
          error_log('some roles are not yet permitted!!');
        }
      }
    }
}
