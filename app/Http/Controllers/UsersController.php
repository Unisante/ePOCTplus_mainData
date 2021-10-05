<?php
namespace App\Http\Controllers;
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
      $user->save();
      $saveCode=PasswordReset::saveReset($user,$random_password);
      if($saveCode){
        $body = 'Your account has been set in Main Data with the default password';
        dispatch(new ResetAccountPasswordJob($body,$email,$user->name,$random_password));
        return back()->with('success', 'Email has been sent to '.$user->name);
      }else{
        return back()->with('error', 'Something Went wrong');
      }
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
    $current_user = Auth::user();
    $request->validate(array(
      'current_password' => 'required|string',
      'new_password' => 'required|string',
    ));
    if (!(Hash::check($request->input('current_password'), $current_user->password))) {
      return back()->with('error', 'Wrong current Password!');
    }
    $current_user->password = Hash::make($request->input('new_password'));
    if($current_user->save()){
      // give new admin role with more permissions if password changed.
      $administrator_id = DB::table('roles')->where('name','Administrator')->select('id')->first();
      Auth::user()->roles()->sync([$administrator_id->id]);

      return redirect()->route('users.profile')->with('success','password has been saved Changed.');
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
