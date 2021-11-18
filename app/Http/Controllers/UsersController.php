<?php
namespace App\Http\Controllers;

use App\Jobs\Generate2faJob;
use Illuminate\Support\Facades\Session;
use App\User;
use App\PasswordReset;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Str;
use App\Jobs\RegisterUserJob;
use App\Jobs\ResetAccountPasswordJob;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
  /**
  * To block any non-authorized user
  * @return void
  */
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('permission:Access_ADMIN_PANEL', ['only' => ['index','create','show','edit']]);
    $this->middleware('permission:Create_User', ['only' => ['store','edit','update','destroy']]);
    $this->middleware('permission:Delete_User', ['only' => ['destroy']]);
  }

  /**
  * Display a listing of the resource.
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request){
    if (Auth::check()){
      $search = $request->input('Search');
      if ($search !=""){
        $users = Approver::where('email','LIKE', '%' . $search . '%')
        ->orWhere('name','LIKE', '%' . $search . '%')
        ->paginate(50);
        return view('users.index',compact('users'));
      } else {
        $users = User::all();
        return view('users.index',compact('users'));
      }
    }
  }

  /**
  * Show the form for creating a new resource.
  * @return \Illuminate\Http\Response
  */
  public function create() {
    $roles=Role::all();
    return view('users.create')->with('roles',$roles);
  }

  /**
  * Store a newly created resource in storage.
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request) {
    $email=Str::lower($request->input('email'));
    if (Auth::check()){
      $validatedData = $request->validate(array(
        'name' => 'required|string',
        'email' => 'required|string|unique:users',
        'role'=>'required',
      ));

      $random_password=Str::random(30);
      while (PasswordReset::where('token',$random_password)->exists()) {
        $random_password=Str::random(30);
      }
      $user=new User;
      $user->name=$request->input('name');
      $user->email=$email;
      $user->password=Hash::make($random_password);
      $user->syncRoles($request->input('role'));
      $saveCode=PasswordReset::saveReset($user,$random_password);
      if(!$saveCode){
        return back()->with('error', 'Something Went wrong');
      }

      # Generate new 2fa secret
      $google2fa = app('pragmarx.google2fa');
      $secret = $google2fa->generateSecretKey();
      $user->google2fa_secret = $secret;
      $user->save();

      # Send it by mail
      try{
        $email_body = 'Your account has been set in Main Data with the default password';
        dispatch(new ResetAccountPasswordJob($email_body, $email, $user->name, $random_password));

        $email_body = 'A new two-factor authentication code has been generated for your account.';
        dispatch(new Generate2faJob($email_body, $user->email, $user->name, $secret));
      }catch(\Exception $e){
        Log::error('Could not send an email to ' . $user->email);
      }

      return back()->with('success', 'Email has been sent to ' . $user->name);
    }
  }

  /**
  * Display the specified resource.
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show(User $user){
    return view('users.show',compact('user'));
  }

  /**
  * Show the form for editing the specified resource.
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit(User $user) {
    $data=array(
      'user'=>$user,
      'roles'=>Role::all(),
    );
    return view('users.edit')->with($data);
  }

  /**
  * Update the specified resource in storage.
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, User $user){
    $validatedData = $request->validate(array(
      'name' => 'required|string',
      'email' => 'required|string',
      'role'=>'required',
    ));

    $user->syncRoles($request->input('role'));
    $user->email = $request->input('email');
    $user->name = $request->input('name');

    if ($user->save()){
      return redirect()->route('users.index')->with('success','Information Updated Successfully');
    }
    else{
      return back()->withinput()->with('errors','Error Updating');
    }
  }

  /**
  * Delete a particular user
  * @param int $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id){
    $user=User::find($id);
    if(DB::table("users")->where('id',$id)->delete()){
      return redirect()->route('users.index')
      ->with('success','User deleted successfully');
    }else{
      return redirect()->route('users.index')
      ->with('error','Something Wrong happened!');
    }
  }

  /**
  * Show current user profile
  * @return \Illuminate\Http\Response
  */
  public function profile(){
    $currentUser=Auth::user();
    return view('users.profile')->with('user',$currentUser);
  }

  /**
   * Change user password
   * @return \Illuminate\Http\Response
   */
  public function showChangePassword(){
    $currentUser=Auth::user();
    return view('users.showPassword')->with('user',$currentUser);
  }

  public function changePassword(Request $request){
    $request->validate(array(
      'current_password' => 'required|string',
      'new_password' => 'required|string',
    ));
    $current_user = Auth::user();
    $current_password = $request->input('current_password');
    $new_password = $request->input('new_password');

    if (!(Hash::check($request->input('current_password'), $current_user->password))) {
      return back()->with('error', 'Wrong current password!');
    }
    if($current_password === $new_password){
      return back()->with('error', 'Password cannot be the same!');
    }

    $current_user->password = Hash::make($request->input('new_password'));
    if($current_user->save()){
      return redirect()->route('users.profile')->with('success','Password has been changed!');
    }else{
      return back()->with('error', 'Something Went wrong');
    }
  }

  public function resetPassword($id){
    $user=User::find($id);
    $random_password=Str::random(30);
    while (PasswordReset::where('token',$random_password)->exists()) {
      $random_password=Str::random(30);
    }
    $saveCode=PasswordReset::saveReset($user,$random_password);
    if($saveCode){
      $body = 'Click this link to reset your password';
      dispatch(new ResetAccountPasswordJob($body,$user->email,$user->name,$random_password));
      return back()->with('success', 'Email has been sent to'.$user->name);
    }else{
      return back()->with('error', 'Something Went wrong');
    }
  }

}
