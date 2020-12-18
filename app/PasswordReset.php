<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use User;

class PasswordReset extends Model
{
  protected $table='custom_password_resets';
  protected $guarded = [];
   public static function saveReset($user,$random_password){
    $user = PasswordReset::updateOrCreate(
      ['email' =>  $user->email],
      ['token'=>$random_password]
    );
    return true;
   }
}
