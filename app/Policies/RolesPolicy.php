<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;

class RolesPolicy
{
    use HandlesAuthorization;

    /**
     * Called before any other authorize method.
     */
    public function before(User $user, $ability){
        if($user->isAdministrator()){
            return true;
        }
    }

    /**
     * Determine whether the user can view any roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('Manage_Roles')
            ? Response::allow()
            : Response::deny('You are not autorized to view roles.');
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        return $user->can('Manage_Roles')
            ? Response::allow()
            : Response::deny('You are not autorized to view this role.');
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Manage_Roles')
            ? Response::allow()
            : Response::deny('You are not autorized to create roles.');
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return $user->can('Manage_Roles')
            ? Response::allow()
            : Response::deny('You are not autorized to update roles.');
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return $user->can('Manage_Roles')
            ? Response::allow()
            : Response::deny('You are not autorized to delete roles.');
    }

    /**
     * Determine whether the user can restore the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function restore(User $user, Role $role)
    {
        return $user->can('Manage_Roles')
            ? Response::allow()
            : Response::deny('You are not autorized to restore roles.');
    }

    /**
     * Determine whether the user can permanently delete the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return mixed
     */
    public function forceDelete(User $user, Role $role)
    {
        return $user->can('Manage_Roles')
            ? Response::allow()
            : Response::deny('You are not autorized to force delete roles.');
    }

    /**
     * Determine wheter the user can remove roles' permissions.
     * 
     * @param \App\User $user
     * @return mixed
     */
    public function removeRolePermission(User $user)
    {
        return $user->can('Manage_Roles')
            ? Response::allow()
            : Response::deny('You are not autorized to remove roles\' permissions.');
    }
}
