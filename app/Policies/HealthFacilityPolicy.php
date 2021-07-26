<?php

namespace App\Policies;

use App\User;
use App\Device;
use App\HealthFacility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class HealthFacilityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any health facilities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
        return Auth::check();
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
        return $user->id == $healthFacility->user_id;
    }

    /**
     * Determine whether the user can create health facilities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Auth::check();
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
        return $user->id == $healthFacility->user_id;
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
        return $user->id == $healthFacility->user_id;
    }


    public function devices(User $user, HealthFacility $healthFacility){
        return $user->id == $healthFacility->user_id;
    }

    public function assignDevice(User $user,HealthFacility $healthFacility, Device $device){
        return $user->id == $device->user_id && $user->id == $healthFacility->user_id;
    }

    public function manageDevices(User $user,HealthFacility $healthFacility){
        return $user->id == $healthFacility->user_id;
    }

    public function unassignDevice(User $user,HealthFacility $healthFacility, Device $device){
        return $user->id == $device->user_id && $user->id == $healthFacility->user_id;
    }

    public function manageAlgorithms(User $user,HealthFacility $healthFacility){
        return $user->id == $healthFacility->user_id;
    }

    public function accesses(User $user,HealthFacility $healthFacility){
        return $user->id == $healthFacility->user_id;
    }

    public function assignVersion(User $user,HealthFacility $healthFacility){
        return $user->id == $healthFacility->user_id;
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
        //
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
        //
    }
}
