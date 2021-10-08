<?php

namespace App;

use App\HealthFacility;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Auditable
{
    use Notifiable,HasRoles,\OwenIt\Auditing\Auditable,HasApiTokens;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
    'name', 'email', 'password', 'google2fa_secret',
  ];

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */
  protected $hidden = [
    'password', 'remember_token', 'google2fa_secret',
  ];

  /**
  * The attributes that should be cast to native types.
  *
  * @var array
  */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
  * Send the password reset notification.
  *
  * @param  string  $token
  * @return void
  */
  public function sendPasswordResetNotification($token)
  {
      $this->notify(new ResetPasswordNotification($token));
  }

  /**
    * Ecrypt the user's google_2fa secret.
    * @param  string  $value
    * @return string
    */
    public function setGoogle2faSecretAttribute($value)
    {
      $this->attributes['google2fa_secret'] = encrypt($value);
    }

  /**
    * Decrypt the user's google_2fa secret.
    *
    * @param  string  $value
    * @return string
    */
    public function getGoogle2faSecretAttribute($value)
    {
      return decrypt($value);
    }



  public function devices(){
    return $this->hasMany(Device::class);
  }

  public function unassignedDevices(){
    return $this->devices->where('health_facility_id',null);
  }

  public function healthFacilities(){
    return $this->hasMany(HealthFacility::class);
  }

}
