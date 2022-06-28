<?php

namespace App\Policies\Supplier;

use App\Entities\User,
    App\Entities\Supplier\Contact;
use Illuminate\Auth\Access\HandlesAuthorization,        
    Illuminate\Auth\Access\Response;

class ContactPolicy
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
     * @param  \App\Models\Supplier\Contact  $contact
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Contact $contact)
    {
        //
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
     * @param  \App\Models\Supplier\Contact  $contact
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Contact $contact)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Models\Supplier\Contact  $contact
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Contact $contact)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Models\Supplier\Contact  $contact
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Contact $contact)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Entities\User  $user
     * @param  \App\Models\Supplier\Contact  $contact
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Contact $contact)
    {
        //
    }
}
