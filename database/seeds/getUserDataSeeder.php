<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\User;

class getUserDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $userDataPath='userDatas/userCredentials.json';
      $users_from_json = json_decode(Storage::get($userDataPath), true);
      foreach($users_from_json as $user){
        $user_created=new User();
        $user_created->name=$user['name'];
        $user_created->email=$user['email'];
        $user_created->password=$user['password'];
        $user_created->created_at=$user['created_at'];
        $user_created->updated_at=$user['updated_at'];
        $user_created->syncRoles($user['role']);
        $user->save();
      }
      error_log("ready set to the database");
    }
}
