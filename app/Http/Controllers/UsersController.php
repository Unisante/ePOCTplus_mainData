<?php
namespace App\Http\Controllers;

use App\Jobs\ResetAccountPasswordJob;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * To block any non-authorized user
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:Access_ADMIN_PANEL', ['only' => ['index', 'create', 'show', 'edit']]);
        $this->middleware('permission:Create_User', ['only' => ['store', 'edit', 'update', 'destroy']]);
        $this->middleware('permission:Delete_User', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return;
        }
        $search = $request->input('Search');
        if ($search == "") {
            $users = User::all();
            return view('users.index', compact('users'));
        }

        $users = Approver::where('email', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->paginate(50);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create')->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $email = Str::lower($request->input('email'));
        if (!Auth::check()) {
            return;
        }
        $validatedData = $request->validate(array(
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'role' => 'required',
        ));

        $random_password = Str::random(30);
        while (PasswordReset::where('token', $random_password)->exists()) {
            $random_password = Str::random(30);
        }
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $email;
        $user->password = Hash::make($random_password);
        $user->syncRoles($request->input('role'));
        $user->save();
        $saveCode = PasswordReset::saveReset($user, $random_password);
        if ($saveCode) {
            $body = 'Your account has been set in Main Data with the default password.';
            dispatch(new ResetAccountPasswordJob($body, $email, $user->name, $random_password));
            Log::info("User with id " . Auth::user()->id . " created a new user.", ["user" => $user]);
            return back()->with('success', 'Email has been sent to ' . $user->name);
        }

        Log::error("User with id " . Auth::user()->id . " tried to create a new user, but something went wrong.", ["user" => $user]);
        return back()->with('error', 'Something Went wrong');
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Log::info("User with id " . Auth::user()->id . " checked out user " . $user->id . ".");
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $data = array(
            'user' => $user,
            'roles' => Role::all(),
        );
        return view('users.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $old_user = clone $user;
        $user->syncRoles($request->input('role'));
        $user->email = $request->input('email');
        $user->name = $request->input('name');

        if ($user->save()) {
            Log::info("User with id " . Auth::user()->id . " updated a user.", ["old_user" => $old_user, "new_user" => $user]);
            return redirect()->route('users.index')->with('success', 'Information Updated Successfully');
        }

        Log::error("User with id " . Auth::user()->id . " tried to update a user, but something went wrong.");
        return back()->withinput()->with('errors', 'Error Updating');
    }

    /**
     * Delete a particular user
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (DB::table("users")->where('id', $id)->delete()) {
            Log::info("User with id " . Auth::user()->id . " deleted a user.", ["user" => $user]);
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        }

        Log::error("User with id " . Auth::user()->id . " tried to delete a user, but something went wrong.");
        return redirect()->route('users.index')->with('error', 'Something Wrong happened!');
    }

    /**
     * Show current user profile
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $currentUser = Auth::user();
        return view('users.profile')->with('user', $currentUser);
    }

    /**
     * Change user password
     * @return \Illuminate\Http\Response
     */
    public function showChangePassword()
    {
        $currentUser = Auth::user();
        return view('users.showPassword')->with('user', $currentUser);
    }

    public function changePassword(Request $request)
    {
        $request->validate(array(
            'current_password' => 'required|string',
            'new_password' => 'required|string',
        ));
        $current_user = Auth::user();
        $current_password = $request->input('current_password');
        $new_password = $request->input('new_password');

        if (!(Hash::check($request->input('current_password'), $current_user->password))) {
            Log::error("User with id " . $current_user->id . " tried to change its password, but entered wrong current password.");
            return back()->with('error', 'Wrong current password!');
        }
        if ($current_password === $new_password) {
            Log::error("User with id " . $current_user->id . " tried to change its password, but the passwords are the same.");
            return back()->with('error', 'Password cannot be the same!');
        }

        $current_user->password = Hash::make($request->input('new_password'));
        if ($current_user->save()) {
            Log::info("User with id " . $current_user->id . " changed its password.");
            return redirect()->route('users.profile')->with('success', 'Password has been changed!');
        } else {
            return back()->with('error', 'Something Went wrong');
        }

        Log::error("User with id " . $current_user->id . " tried to change its password, but something went wrong.");
        return back()->with('error', 'Something Went wrong');
    }

    public function resetPassword($id)
    {
        $user = User::find($id);
        $random_password = Str::random(30);
        while (PasswordReset::where('token', $random_password)->exists()) {
            $random_password = Str::random(30);
        }

        $saveCode = PasswordReset::saveReset($user, $random_password);
        if ($saveCode) {
            $body = 'Click this link to reset your password';
            dispatch(new ResetAccountPasswordJob($body, $user->email, $user->name, $random_password));
            Log::info("The password of user with id " . $user->id . " has been reset by user with id " . Auth::user()->id . ".");
            return back()->with('success', 'Email has been sent to' . $user->name);
        }

        Log::error("Tried to reset the password of user with id " . $user->id . ", but something went wrong.");
        return back()->with('error', 'Something Went wrong');
    }

}
