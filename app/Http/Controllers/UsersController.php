<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;


class UsersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('permission:View Admin Panel');
  }
  /**
  * To block any non-authorized user
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth');
  }
  /**
  * Display a listing of the resource.
  *
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
  *
  * @return \Illuminate\Http\Response
  */
  public function create() {
    return view('users.create');
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request) {
    if (Auth::check()){
      $validatedData = $request->validate(array(
        'name' => 'required|string',
        'email' => 'required|string',
      ));
        $user=new User;
        $user->name=$request->input('name');
        $user->email=$request->input('email');
        $user->password=Hash::make($request->input('password'));
      if($user->save()){
        return redirect()->route('user.index')->with('success','Information have been saved Successfully.');;

      }
      else{
        return back()->withinput()->with('errors','Error Occured, Probably this user exist');
      }
    }
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show(User $user) {
    return view('users.show',compact('user'));
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit(User $user) {
    return view('users.edit',compact('user'));
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, User $user) {
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
}
