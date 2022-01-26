<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Role::class);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(5);
        return view('roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('roles.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        Log::info("User with id " . Auth::user()->id . " created a new role.", ["role" => $role]);
        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $role->id)
            ->get();
        $permission = Permission::get();
        Log::info("User with id " . Auth::user()->id . " checked out role " . $role->id . ".");
        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($role->id);
        $old_role = clone $role;
        $role->givePermissionTo($request->input('permission'));
        Log::info("User with id " . Auth::user()->id . " updated a role.", ["old_role" => $old_role, "new_role" => $role]);
        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $name = $role->name;
        foreach (User::all() as $user) {
            foreach ($user->getRoleNames()->toArray() as $userRole) {
                if ($userRole == $name) {
                    return redirect()->route('roles.index')
                        ->with('error', 'Role Assigned to a User!');
                }
            }
        }
        DB::table("roles")->where('id', $role->id)->delete();
        Log::info("User with id " . Auth::user()->id . " removed a role.", ["role" => $role]);
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }

    /**
     * Remove Roles permision
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function removeRolePermissionShow($id)
    {
        $this->authorize('removeRolePermission', Role::class);

        $role = Role::find($id);
        $permissions = $role->permissions()->get();
        return view('roles.removePerm', compact('role', 'permissions'));
    }

    /**
     * Remove the specified permission in role.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeRolePermission(Request $request, $id)
    {
        $this->authorize('removeRolePermission', Role::class);

        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $old_role = clone $role;
        if ($role->revokePermissionTo($request->input('permission'))) {
            Log::info("User with id " . Auth::user()->id . " removed a permission to a role.", ["old_role" => $old_role, "new_role" => $role]);
            return redirect()->route('roles.index')
                ->with('success', 'Role Removed successfully');
        }
    }
}
