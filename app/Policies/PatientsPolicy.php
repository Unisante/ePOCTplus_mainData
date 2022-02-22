<?php

namespace App\Policies;

use App\Patient;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PatientsPolicy
{
    use HandlesAuthorization;

    /**
     * Called before any other authorize method.
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdministrator()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any patients.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('View_Patients') || $user->can('Manage_Patients')
            ? Response::allow()
            : Response::deny('You are not autorized to view patients.');
    }

    /**
     * Determine whether the user can view the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function view(User $user, Patient $patient)
    {
        return $user->can('View_Patients') || $user->can('Manage_Patients')
            ? Response::allow()
            : Response::deny('You are not autorized to view this patient.');
    }

    /**
     * Determine whether the user can create patients.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Manage_Patients')
            ? Response::allow()
            : Response::deny('You are not autorized to create patients.');
    }

    /**
     * Determine whether the user can update the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function update(User $user, Patient $patient)
    {
        return $user->can('Manage_Patients')
            ? Response::allow()
            : Response::deny('You are not autorized to update patients.');
    }

    /**
     * Determine whether the user can delete the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function delete(User $user, Patient $patient)
    {
        return $user->can('Manage_Patients')
            ? Response::allow()
            : Response::deny('You are not autorized to delete patients.');
    }

    /**
     * Determine whether the user can restore the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function restore(User $user, Patient $patient)
    {
        return $user->can('Manage_Patients')
            ? Response::allow()
            : Response::deny('You are not autorized to restore patients.');
    }

    /**
     * Determine whether the user can permanently delete the patient.
     *
     * @param  \App\User  $user
     * @param  \App\Patient  $patient
     * @return mixed
     */
    public function forceDelete(User $user, Patient $patient)
    {
        return $user->can('Manage_Patients')
            ? Response::allow()
            : Response::deny('You are not autorized to force delete patients.');
    }

    /**
     * Determine whether the user can manage merge duplicates.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function duplicate(User $user)
    {
        return $user->can('Manage_Patients_Merge_Duplicates')
            ? Response::allow()
            : Response::deny('You are not autorized to check patients\' duplicates.');
    }

    /**
     * Determine whether the user can manage merge duplicates.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function merge(User $user)
    {
        return $user->can('Manage_Patients_Merge_Duplicates')
            ? Response::allow()
            : Response::deny('You are not autorized to merge patients.');
    }
}
