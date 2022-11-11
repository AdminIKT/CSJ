<?php

namespace App\Policies;

use App\Entities\User;
use App\Entities\Action;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActionPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Entities\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }
    
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Entities\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return \Illuminate\Auth\Access\Response::deny("You cannot show actions list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Action  $action
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Action $action)
    {
        return Response::deny("You cannot show action detail");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Entities\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return Response::deny("You cannot create a new action");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Action  $action
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Action $action)
    {
        return Response::deny("You cannot update an action");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Action  $action
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Action $action)
    {
        return Response::deny("You cannot delete an action");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Action  $action
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Action $action)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Action  $action
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Action $action)
    {
        //
    }
}
