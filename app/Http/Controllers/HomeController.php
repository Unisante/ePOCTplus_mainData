<?php

namespace App\Http\Controllers;
use App\Services\RedCapApiService;
use Auth;
use App\User;
use App\PasswordReset;
use App\MedicalCase;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        $this->middleware(['auth', '2fa'], ['except' => ['forgotPassword','makePassword','checkToken']]);
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

    public function reauthenticate(Request $request)
    {
      $user = Auth::user();

      $google2fa = app('pragmarx.google2fa');
      $user->google2fa_secret = $google2fa->generateSecretKey();
      $user->save();

      // generate the QR image
      $QR_Image = $google2fa->getQRCodeInline(
        config('app.name'),
        $user->email,
        $user->google2fa_secret
      );

      // Pass the QR barcode image to our view
      return view('google2fa.register', [
        'QR_Image' => $QR_Image, 
        'secret' => $user->google2fa_secret,
        'reauthenticating' => true
      ]);
    }

    public function forgotPassword(Request $request){
      $email=Str::lower($request->email);
      $userNotIn=User::where('email',$email)->doesntExist();
      if($userNotIn){
        $message="Email doesn't exist in main data, please contact the admin";
        return Redirect::back()->with(['error'=>$message]);
      }
      $random_password=Str::random(30);
      while (PasswordReset::where('token',$random_password)->exists()) {
        $random_password=Str::random(30);
      }
      $user=User::where('email',$email)->first();
      $saveCode=PasswordReset::saveReset($user,$random_password);

      if($saveCode){
        $body = 'Click this link to reset your password';
        dispatch(new ResetAccountPasswordJob($body,$email,$user->name,$random_password));
        $message="Email has been sent to you for password reset";
        return Redirect::back()->with(['success'=>$message]);
      }
    }
    public function checkToken($id, Request $request){
      $code_exist=PasswordReset::where('token',$id)->exists();
      $request->session()->put('reset_token', $id);
      if($code_exist){
        return view ('emails.reset')->with(['token'=>$id]);
      }
      return view('errors.404');
    }
  public function makePassword(Request $request){
    $token = $request->session()->get('reset_token');
    $code=PasswordReset::where('token',$token)->first();
    $request->session()->forget('reset_token');
    $user=User::where('email',$code->email)->update(
          [
            'password'=>Hash::make($request->password)
          ]
    );

    if($user){
      $user_to_log=User::where('email',$code->email)->first();
      if (Auth::attempt(['email' => $user_to_log->email, 'password' => $user_to_log->password]))
        {
          return redirect()->action(
            'HomeController@index'
          );
        }
      return redirect('/');
    }else{
      $message="Something went Wrong,please retry or contact the administrator for help";
      return Redirect::back()->with(['error'=>$message]);
    }
  }
}
