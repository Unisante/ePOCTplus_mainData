<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use App\User;

class setUserDataSeeder extends Seeder
{
  use HasRoles;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $userDataPath='userDatas/userCredentials.json';
      $users_from_database = DB::table('users')->get();
      $users_from_database->each(function($user){
        // User::with('roles')->get()
        $userRoles=User::with('roles')->where('email',$user->email)->first();
        $user->role=json_encode($userRoles->roles->first()->name);
        // error_log(json_encode($userRoles->roles->first()->name));
        // error_log($userRoles->name);
        // implode($userRoles->getRoleNames());
        // error_log(implode($userRoles->getRoleNames()));
        // error_log($user->role);
      });
      Storage::put($userDataPath, $users_from_database);
      error_log("ready set to the json file");
    }
}
