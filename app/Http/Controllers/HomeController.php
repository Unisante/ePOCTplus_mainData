<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Contracts\Support\Renderable
  */
  public function index()
  {
    $user = Auth::user();
    view()->composer('adminlte::page', function($view)
    {
        $user = Auth::user();
        $view->with("user", $user);
    });
    return view("home", $user);
  }
}
