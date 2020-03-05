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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()){

            $Search = $request->input('Search');
            if ($Search !=""){
                $users = Approver::where('id','LIKE', '%' . $Search . '%')
                ->orWhere('email','LIKE', '%' . $Search . '%')
                ->orWhere('name','LIKE', '%' . $Search . '%')
                ->paginate(50);
        return view('users.index',compact('users'));
            }else{
                \Session::put('search1', '');
            $users = User::where('id','!=', 0)->paginate(10);
            return view('users.index',compact('users'));
        }
    }
    }


   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

         if (Auth::check()){
//
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string',
                ]);
           $addusers=User::create([
                     'email'=>$request->input('email'),
                     'name'=>$request->input('name'),
                     ]);

                     if($addusers){
                return redirect()->route('user.index')->with('success','Information have been saved Successfully.');;

        }else{
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
    public function show(User $user)
    {
        //
        $users  = User::find($user->id);
        return view('users.show',compact('users'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $users  = User::find($user->id);
        return view('users.edit',compact('users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            ]);
        $users=User::where('id', $user->id)
                ->update([
                    'email'=>$request->input('email'),
                    'name'=>$request->input('name'),
                        ]);

                    if ($users){
                           return redirect()->route('user.index')->with('success','Information Updated Successfully');
                     }

                          return back()->withinput()->with('errors','Error Updating');
                     }


    
   
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
