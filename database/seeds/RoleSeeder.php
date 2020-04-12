<?php

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
        $roles=[
          "admin",
          "data_manager",
          "clinivisor_user",
          "e_mergence_User"
        ];
        foreach($roles as $role){
          Role::create([
            'name'=>$role,
          ]);
        }
    }
}
