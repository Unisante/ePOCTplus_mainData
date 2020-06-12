<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use App\MedicalCase;
use App\Patient;
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
      $data=array(
        'currentUser'=>Auth::user(),
        'userCount'=>User::all()->count(),
        'mdCases'=> MedicalCase::all()->count(),
        'patientCount'=> Patient::all()->count(),
      );
        return view('home')->with($data);
    }
}
