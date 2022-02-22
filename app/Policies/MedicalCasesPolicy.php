<?php

namespace App\Policies;

use App\MedicalCase;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MedicalCasesPolicy
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
     * Determine whether the user can view any medical cases.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('View_Medical_Cases') || $user->can('Manage_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to view medical cases.');
    }

    /**
     * Determine whether the user can view the medical case.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalCase  $medicalCase
     * @return mixed
     */
    public function view(User $user, MedicalCase $medicalCase)
    {
        return $user->can('View_Medical_Cases') || $user->can('Manage_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to view this medical case.');
    }

    /**
     * Determine whether the user can create medical cases.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Manage_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to create medical cases.');
    }

    /**
     * Determine whether the user can update the medical case.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalCase  $medicalCase
     * @return mixed
     */
    public function update(User $user, MedicalCase $medicalCase)
    {
        return $user->can('Manage_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to update medical cases.');
    }

    /**
     * Determine whether the user can delete the medical case.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalCase  $medicalCase
     * @return mixed
     */
    public function delete(User $user, MedicalCase $medicalCase)
    {
        return $user->can('Manage_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to delete medical cases.');
    }

    /**
     * Determine whether the user can restore the medical case.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalCase  $medicalCase
     * @return mixed
     */
    public function restore(User $user, MedicalCase $medicalCase)
    {
        return $user->can('Manage_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to restore medical cases.');
    }

    /**
     * Determine whether the user can permanently delete the medical case.
     *
     * @param  \App\User  $user
     * @param  \App\MedicalCase  $medicalCase
     * @return mixed
     */
    public function forceDelete(User $user, MedicalCase $medicalCase)
    {
        return $user->can('Manage_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to force delete medical cases.');
    }

    /**
     * Determine whether the user can compare medical cases.
     *
     * @param \App\User
     * @return mixed
     */
    public function compare(User $user, MedicalCase $medicalCase)
    {
        return $user->can('View_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to compare medical cases.');
    }

    /**
     * Determine whether the user can view medical cases' questions.
     *
     * @param \App\User
     * @return mixed
     */
    public function question(User $user)
    {
        return $user->can('View_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to view medical cases\' questions.');
    }

    /**
     * Determine whether the user can view medical cases' changes.
     *
     * @param \App\User
     * @return mixed
     */
    public function changes(User $user)
    {
        return $user->can('View_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to view medical cases\' changes.');
    }

    /**
     * Determine whether the user can view medical cases' duplicates.
     *
     * @param \App\User
     * @return mixed
     */
    public function duplicates(User $user)
    {
        return $user->can('View_Medical_Cases')
        ? Response::allow()
        : Response::deny('You are not authorized to view medical cases\' duplicates.');
    }

    /**
     * Determine whether the user can view medical cases' followups.
     *
     * @param \App\User
     * @return mixed
     */
    public function followUp(User $user)
    {
        return $user->can('View_Follow_Ups')
        ? Response::allow()
        : Response::deny('You are not authorized to view follow-ups.');
    }
}
