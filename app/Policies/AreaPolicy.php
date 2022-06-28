<?php

namespace App\Policies;

use App\Entities\User;
use App\Entities\Area;
use Illuminate\Auth\Access\HandlesAuthorization;

class AreaPolicy
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
        return Response::deny("You cannot show areas list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Area  $area
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Area $area)
    {
        return Response::deny("You cannot show area detail");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Entities\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return Response::deny("You cannot create a new area");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Area  $area
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Area $area)
    {
        return Response::deny("You cannot update an area");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Area  $area
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Area $area)
    {
        return Response::deny("You cannot delete an area");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Area  $area
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Area $area)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Area  $area
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Area $area)
    {
        //
    }
}
