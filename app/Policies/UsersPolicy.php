<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class UsersPolicy
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
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('Manage_Users')
            ? Response::allow()
            : Response::deny('You are not autorized to view users.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        // Users without Manage_Users permission can only see theirselves.
        return $user->can('Manage_Users') || Auth::user()->id == $model->id
            ? Response::allow()
            : Response::deny('You are not autorized to view this user.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Manage_Users')
            ? Response::allow()
            : Response::deny('You are not autorized to create users.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->can('Manage_Users')
            ? Response::allow()
            : Response::deny('You are not autorized to update users.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return $user->can('Manage_Users')
            ? Response::allow()
            : Response::deny('You are not autorized to delete users.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        return $user->can('Manage_Users')
            ? Response::allow()
            : Response::deny('You are not autorized to restore users.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        return $user->can('Manage_Users')
            ? Response::allow()
            : Response::deny('You are not autorized to force delete users.');
    }

    /**
     * Determine whether the user can change its own password.
     * 
     * @param \App\User $user
     * @return mixed
     */
    public function changePassword(User $user)
    {
        return $user->can('Reset_Own_Password')
            ? Response::allow()
            : Response::deny('You are not autorized to change your own password.');
    }

    /**
     * Determine whether the user can reset anyone's password.
     * 
     * @param \App\User $user
     * @param \App\User $model
     * @return mixed
     */
    public function resetPassword(User $user, User $model)
    {
        return $user->can('Manage_Users')
            ? Response::allow()
            : Response::deny('You are not autorized to change users\' password.');
    }
}
