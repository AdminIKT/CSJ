<?php

namespace App\Policies;

use App\Entities\User;
use App\Entities\Account;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AccountPolicy
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
        if ($ability === 'delete') {
            return;
        }
        elseif ($user->isAdmin()) {
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
     * @param  \App\Entities\Account  $account
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Account $account)
    {
        return $account->getUsers()->contains($user)
            ? Response::allow()
            : Response::deny("You do not own this account");
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
     * @param  \App\Entities\Account  $account
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Account $account)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Account  $account
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Account $account)
    {
        return $user->isAdmin() &&
               $account->getOrders()->count() === 0
               ? Response::allow()
               : Response::deny("Order cannot be deleted");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Account  $account
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Account $account)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Account  $account
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Account $account)
    {
        //
    }
}
