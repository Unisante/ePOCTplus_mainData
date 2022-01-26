<?php

namespace App\Policies;

use App\MedicalStaff;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MedicalStaffPolicy
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
     * Determine whether the user can view any medical staff.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('View_Medical_Staff') || $user->can('Manage_Medical_Staff')
            ? Response::allow()
            : Response::deny('You are not authorized to see medical staff.');
    }

    /**
     * Determine whether the user can view the medical staff.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalStaff  $medicalStaff
     * @return mixed
     */
    public function view(User $user, MedicalStaff $medicalStaff)
    {
        return $user->can('View_Medical_Staff') || $user->can('Manage_Medical_Staff')
            ? Response::allow()
            : Response::deny('You are not authorized to see this medical staff.');
    }

    /**
     * Determine whether the user can create medical staff.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Manage_Medical_Staff')
            ? Response::allow()
            : Response::deny('You are not authorized to create medical staff.');
    }

    /**
     * Determine whether the user can update the medical staff.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalStaff  $medicalStaff
     * @return mixed
     */
    public function update(User $user, MedicalStaff $medicalStaff)
    {
        return $user->can('Manage_Medical_Staff')
            ? Response::allow()
            : Response::deny('You are not authorized to update medical staff.');
    }

    /**
     * Determine whether the user can delete the medical staff.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalStaff  $medicalStaff
     * @return mixed
     */
    public function delete(User $user, MedicalStaff $medicalStaff)
    {
        return $user->can('Manage_Medical_Staff')
            ? Response::allow()
            : Response::deny('You are not authorized to delete medical staff.');
    }

    /**
     * Determine whether the user can restore the medical staff.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalStaff  $medicalStaff
     * @return mixed
     */
    public function restore(User $user, MedicalStaff $medicalStaff)
    {
        return $user->can('Manage_Medical_Staff')
            ? Response::allow()
            : Response::deny('You are not authorized to restore medical staff.');
    }

    /**
     * Determine whether the user can permanently delete the medical staff.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalStaff  $medicalStaff
     * @return mixed
     */
    public function forceDelete(User $user, MedicalStaff $medicalStaff)
    {
        return $user->can('Manage_Medical_Staff')
            ? Response::allow()
            : Response::deny('You are not authorized to force delete medical staff.');
    }
}
