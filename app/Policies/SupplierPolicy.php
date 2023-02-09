<?php

namespace App\Policies;

use App\Entities\User;
use App\Entities\Supplier;
use Illuminate\Auth\Access\HandlesAuthorization,        
    Illuminate\Auth\Access\Response;

class SupplierPolicy
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
        if ($user->isAdmin() && $ability !== 'delete') {
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
        return $user->isSales()
               ? Response::allow()
               : Response::deny("You cannot show suppliers list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Supplier  $supplier
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Supplier $supplier)
    {
        return !$supplier->isInactive()
               ? Response::allow()
               : Response::deny("You cannot show supplier detail");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Entities\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Supplier  $supplier
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Supplier $supplier)
    {
        return Response::deny("You cannot edit suppliers");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Supplier  $supplier
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Supplier $supplier)
    {
        return $user->isAdmin() &&
               $supplier->getOrders()->count() === 0
               ? Response::allow()
               : Response::deny("Supplier cannot be deleted");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Supplier  $supplier
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Supplier $supplier)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Supplier  $supplier
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Supplier $supplier)
    {
        //
    }
}
