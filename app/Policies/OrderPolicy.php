<?php

namespace App\Policies;

use App\Entities\User;
use App\Entities\Order;
use App\Entities\Subaccount;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class OrderPolicy
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
        return Response::deny("You cannot show orders list");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Order $order)
    {
        return $order->getAccount()->getUsers()->contains($user)
            ? Response::allow()
            : Response::deny("You do not own this order");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Entities\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, $account = null)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Order $order)
    {
        return Response::deny("You cannot update an order");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Order $order)
    {
        return $user->isAdmin() &&
               $order->isStatus(Order::STATUS_CREATED)
               ? Response::allow()
               : Response::deny("The order cannot be deleted");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Entities\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Order $order)
    {
        //
    }
}
