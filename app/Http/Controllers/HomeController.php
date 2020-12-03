<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use App\MedicalCase;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Jobs\ResetAccountPasswordJob;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['forgotPassword']]);
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
      error_log(Auth::user()->getPermissionsViaRoles());
      return view('home')->with($data);
    }

    public function forgotPassword(Request $request){
      $userNotIn=User::where('email',$request->email)->doesntExist();
      if($userNotIn){
        $message="Email doesn't exist in main data, please contact the admin";
        return Redirect::back()->withErrors($message);
      }
      $random_password=Str::random(10);
      $user=User::where('email',$request->email)->update(
        [
          'password'=>Hash::make($random_password)
        ]
      );
      if($user){
        $body = 'Your password has been reset to '. $random_password;
        dispatch(new ResetAccountPasswordJob($body,$request->email));
        return redirect('/');
      }
    }
}
