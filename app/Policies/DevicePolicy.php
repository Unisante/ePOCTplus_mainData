<?php

namespace App\Policies;

use App\User;
use App\Device;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DevicePolicy
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
     * Determine whether the user can view any devices.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('View_Devices') || $user->can('Manage_Devices')
            ? Response::allow()
            : Response::deny('You are not authorized to see devices.');
    }

    /**
     * Determine whether the user can view the device.
     *
     * @param  \App\User  $user
     * @param  \App\Device  $device
     * @return mixed
     */
    public function view(User $user, Device $device)
    {
        return $user->can('View_Devices') || $user->can('Manage_Devices')
            ? Response::allow()
            : Response::deny('You are not authorized to see this device.');
    }

    /**
     * Determine whether the user can create devices.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Manage_Devices')
            ? Response::allow()
            : Response::deny('You are not authorized to create devices.');
    }

    /**
     * Determine whether the user can update the device.
     *
     * @param  \App\User  $user
     * @param  \App\Device  $device
     * @return mixed
     */
    public function update(User $user, Device $device)
    {
        return $user->can('Manage_Devices')
            ? Response::allow()
            : Response::deny('You are not authorized to update devices.');
    }

    /**
     * Determine whether the user can delete the device.
     *
     * @param  \App\User  $user
     * @param  \App\Device  $device
     * @return mixed
     */
    public function delete(User $user, Device $device)
    {
        return $user->can('Manage_Devices')
            ? Response::allow()
            : Response::deny('You are not authorized to delete devices.');
    }

    /**
     * Determine whether the user can restore the device.
     *
     * @param  \App\User  $user
     * @param  \App\Device  $device
     * @return mixed
     */
    public function restore(User $user, Device $device)
    {
        return $user->can('Manage_Devices')
            ? Response::allow()
            : Response::deny('You are not authorized to restore devices.');
    }

    /**
     * Determine whether the user can permanently delete the device.
     *
     * @param  \App\User  $user
     * @param  \App\Device  $device
     * @return mixed
     */
    public function forceDelete(User $user, Device $device)
    {
        return $user->can('Manage_Devices')
            ? Response::allow()
            : Response::deny('You are not authorized to force delete devices.');
    }
}
