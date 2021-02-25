<?php

use Illuminate\Database\Seeder;
use App\User;

class MakeAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $username='MainData';
      $email='dynamicifakara@gmail.com';
      $password ='Dynamic123';
      $role = 'ADMIN';
      if(User::where([['name',$username],['email',$email]])->doesntExist()){
        $user=new User;
        $user->name=$username;
        $user->email=$email;
        $user->password=Hash::make($password);
        $user->syncRoles($role);
        $user->save();
      }
    }
}
