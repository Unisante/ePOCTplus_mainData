<?php

namespace App\Policies;

use App\Node;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class QuestionsPolicy
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
     * Determine whether the user can view any nodes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('View_Questions')
            ? Response::allow()
            : Response::deny('You are not authorized to see questions.');
    }

    /**
     * Determine whether the user can view the node.
     *
     * @param  \App\User  $user
     * @param  \App\Node  $node
     * @return mixed
     */
    public function view(User $user, Node $question)
    {
        return $user->can('View_Questions')
            ? Response::allow()
            : Response::deny('You are not authorized to see this question.');
    }
}
