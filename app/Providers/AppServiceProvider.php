<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Auth;

class AppServiceProvider extends ServiceProvider
{
  /**
  * Register any application services.
  *
  * @return void
  */
  public function register()
  {
    //
  }

  /**
  * Bootstrap any application services.
  *
  * @return void
  */
  public function boot(Dispatcher $events)
  {

    $events->listen(BuildingMenu::class, function(BuildingMenu $event){
      foreach(Auth::user()->roles as $role){

        $event->menu->add($role->name);
      }
      $event->menu->add([
        'text' => 'Duplicates',
        'icon' => 'fas fa-fw fa-clone',
        'can'=>'Merge_Duplicates',
        'submenu' => [
          [
            'text' => 'Patients',
            'url'  => '/patients/duplicates',
            'icon' => 'fas fa-fw fa-users',
          ],
          [
            'text' => 'Medical Cases',
            'url'  => '/medicalcases/duplicates',
            'icon' => 'fas fa-fw fa-pencil-alt',
          ],
        ],
      ],);
      $event->menu->add([
        'text' => 'Patient list',
        'url'  => '/patients',
        'icon' => 'fas fa-fw fa-list',
        'can'=>'View_Patient'

      ],);
      $event->menu->add([
        'text' => 'Medical Cases',
        'url'  => '/medicalCases',
        'icon' => 'fas fa-fw fa-file',
        'can'=>'View_Case'
      ],);
      $event->menu->add([
        'text' => 'Questions',
        'url'  => '/questions',
        'icon' => 'fas fa-fw fa-question-circle',
        'can'=>'View_Case'
      ],);
      $event->menu->add([
        'text' => 'profile',
        'url'  => '/user/profile',
        'icon' => 'fas fa-fw fa-user',
        'can'=>'Reset_Own_Password'
      ],);
      $event->menu->add([
        'text' => 'change_password',
        'url'  => '/user/password',
        'icon' => 'fas fa-fw fa-lock',
        'can'=>'Reset_Own_Password'
      ],);
    $event->menu->add([
      'text' => 'Admin Corner',
      'icon' => 'fas fa-fw fa-cog',
      'can' => 'Access_ADMIN_PANEL',

      'submenu' => [
        [
          'text' => 'Users Management',
          'url'  => '/users',
          'icon' => 'fas fa-fw fa-users',
        ],


        [
          'text' => 'Manage Roles',
          'url'  => '/roles',
          'icon' => 'fas fa-fw fa-pencil-alt',
        ],
      ],
    ],);
  });
  //
}
}
