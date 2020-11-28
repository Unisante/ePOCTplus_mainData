<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use DB;

class RolesController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('role:Administrator');
  }

  /**
  * Display a listing of the resource.
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request){
    $roles = Role::orderBy('id','DESC')->paginate(5);
    return view('roles.index',compact('roles'))
    ->with('i', ($request->input('page', 1) - 1) * 5);
  }

  /**
  * Show the form for creating a new resource.
  * @return \Illuminate\Http\Response
  */
  public function create(){
    $permission = Permission::get();
    return view('roles.create',compact('permission'));
  }

  /**
  * Store a newly created resource in storage.
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request){
    $this->validate($request, [
      'name' => 'required|unique:roles,name',
      'permission' => 'required',
    ]);
    $role = Role::create(['name' => $request->input('name')]);
    $role->syncPermissions($request->input('permission'));
    return redirect()->route('roles.index')
    ->with('success','Role created successfully');
  }

  /**
  * Display the specified resource.
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id){
    $role = Role::find($id);
    $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
    ->where("role_has_permissions.role_id",$id)
    ->get();
    $permission = Permission::get();
    return view('roles.show',compact('role','rolePermissions',));
  }

  /**
  * Show the form for editing the specified resource.
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id){
    $role = Role::find($id);
    $permission = Permission::get();
    $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
    ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
    ->all();
    return view('roles.edit',compact('role','permission','rolePermissions'));
  }


  /**
  * Update the specified resource in storage.
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id){
    $this->validate($request, [
      'name' => 'required',
      'permission' => 'required',
    ]);
    $role = Role::find($id);
    $role->givePermissionTo($request->input('permission'));
    return redirect()->route('roles.index')
    ->with('success','Role updated successfully');
  }

  /**
  * Remove the specified resource from storage.
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id){
    $role=Role::find($id)->name;
    foreach(User::all() as $user){
      foreach($user->getRoleNames()->toArray() as $userRole){
        if($userRole==$role){
          return redirect()->route('roles.index')
          ->with('error','Role Assigned to a User!');
        }
      }
    }
    DB::table("roles")->where('id',$id)->delete();
    return redirect()->route('roles.index')
    ->with('success','Role deleted successfully');
  }

  /**
  * Remove Roles permision
  * @param $id
  * @return \Illuminate\Http\Response
  */
  public function removeRolePermissionShow($id){
    $role = Role::find($id);
    $permissions = $role->permissions()->get();
    return view('roles.removePerm',compact('role','permissions'));
  }

  /**
  * Remove the specified permission in role.
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function removeRolePermission(Request $request, $id){
    $this->validate($request, [
      'name' => 'required',
      'permission' => 'required',
    ]);
    $role = Role::find($id);
    if($role->revokePermissionTo($request->input('permission'))){
      return redirect()->route('roles.index')
      ->with('success','Role Removed successfully');
    }
  }
}
