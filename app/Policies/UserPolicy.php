<?php

namespace App\Policies;

use App\Entities\User;
use App\Entities\Setting;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\User  $entity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $entity)
    {
        return $user === $entity;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Entities\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\User  $entity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $entity)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\User  $entity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $entity)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\User  $entity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $entity)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\User  $entity
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $entity)
    {
        //
    }
}
