<?php

namespace App\Policies;

use App\User;
use App\MedicalStaff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicalStaffPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any medical staff.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    { 
        return true; // TODO
    }

    /**
     * Determine whether the user can view the medical staff.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalStaff  $medical_staff
     * @return mixed
     */
    public function view(User $user, MedicalStaff $medical_staff)
    {
        return true; // TODO
    }

    /**
     * Determine whether the user can create the medical staff.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true; // TODO
    }

    /**
     * Determine whether the user can update the device.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalStaff  $medical_staff
     * @return mixed
     */
    public function update(User $user, MedicalStaff $medical_staff)
    {
        return true; // TODO
    }

    /**
     * Determine whether the user can delete the device.
     *
     * @param  \App\User  $user
     * @param  \App\Device  $device
     * @return mixed
     */
    public function delete(User $user, MedicalStaff $medical_staff)
    {
        return true; // TODO
    }

    /**
     * Determine whether the user can restore the device.
     *
     * @param  \App\User  $user
     * @param  \App\Device  $device
     * @return mixed
     */
    public function restore(User $user, MedicalStaff $medical_staff)
    {
        return true; // TODO
    }

    /**
     * Determine whether the user can permanently delete the medical_staff.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalStaff  $medical_staff
     * @return mixed
     */
    public function forceDelete(User $user, MedicalStaff $medical_staff)
    {
        return true; // TODO
    }
}
