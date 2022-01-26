<?php

namespace App\Policies;

use App\User;
use App\Device;
use App\HealthFacility;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class HealthFacilityPolicy
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
     * Determine whether the user can view any health facilities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('View_Health_Facilities') || $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to view health facilities.');
    }

    /**
     * Determine whether the user can view the health facility.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function view(User $user, HealthFacility $healthFacility)
    {
        return $user->can('View_Health_Facilities') || $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to view this health facility.');
    }

    /**
     * Determine whether the user can create health facilities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to create health facilities.');
    }

    /**
     * Determine whether the user can update the health facility.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function update(User $user, HealthFacility $healthFacility)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to update health facilities.');
    }

    /**
     * Determine whether the user can delete the health facility.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function delete(User $user, HealthFacility $healthFacility)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to delete health facilities.');
    }

    /**
     * Determine whether the user can view health facilities' devices.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function devices(User $user, HealthFacility $healthFacility)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to view health facilities\' devices.');
    }

    /**
     * Determine whether the user can assign devices to health facilities.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function assignDevice(User $user,HealthFacility $healthFacility, Device $device)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to assign devices to health facilities.');
    }

    /**
     * Determine whether the user can manage health facilities' devices.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function manageDevices(User $user,HealthFacility $healthFacility)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to manage health facilities\' devices.');
    }

    /**
     * Determine whether the user can unassign devices to health facilities.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function unassignDevice(User $user,HealthFacility $healthFacility, Device $device)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to unassign devices to health facilities.');
    }

    /**
     * Determine whether the user can manage health facilities' algorithms.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function manageAlgorithms(User $user,HealthFacility $healthFacility)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to manage health facilities\' algorithms.');
    }

    /**
     * Determine whether the user can view health facilities' accesses.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function accesses(User $user, HealthFacility $healthFacility)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to view health facilities\' accesses.');
    }

    /**
     * Determine whether the user can view health facilities' versions.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function versions(User $user, HealthFacility $healthFacility)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to view health facilities\' versions.');
    }

    /**
     * Determine whether the user can assign versions to health facilities.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function assignVersion(User $user,HealthFacility $healthFacility)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to assign versions to health facilities.');
    }

    /**
     * Determine whether the user can restore the health facility.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function restore(User $user, HealthFacility $healthFacility)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to restore health facilities.');
    }

    /**
     * Determine whether the user can permanently delete the health facility.
     *
     * @param  \App\User  $user
     * @param  \App\HealthFacility  $healthFacility
     * @return mixed
     */
    public function forceDelete(User $user, HealthFacility $healthFacility)
    {
        return $user->can('Manage_Health_Facilities')
            ? Response::allow()
            : Response::deny('You are not autorized to force delete health facilities.');
    }
}
