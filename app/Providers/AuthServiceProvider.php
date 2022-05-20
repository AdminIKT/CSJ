<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate as G;
use App\Entities\User,
    App\Entities\Order;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        G::define('order-show', function (User $user, Order $e) {
            return $e->getAccount()->getUsers()->contains($user);
        });
        G::define('order-update', function (User $user, Order $e) {
            return $e->getAccount()->getUsers()->contains($user);
        });
        G::define('order-delete', function (User $user, Order $e) {
            return $e->isPending() && $e->getAccount()->getUsers()->contains($user);
        });



        G::define('order-status-received', function(User $user, Order $e) {
            return $e->isStatus(Order::STATUS_CREATED);
        });
        G::define('order-status-checked-not-agreed', function(User $user, Order $e) {
            return $e->isStatus(Order::STATUS_RECEIVED) ||
                   $e->isStatus(Order::STATUS_CHECKED_PARTIAL_AGREED) ||
                   $e->isStatus(Order::STATUS_CHECKED_AGREED)
                   ;
        });
        G::define('order-status-checked-partial-agreed', function(User $user, Order $e) {
            return $e->isStatus(Order::STATUS_RECEIVED) ||
                   $e->isStatus(Order::STATUS_CHECKED_NOT_AGREED) ||
                   $e->isStatus(Order::STATUS_CHECKED_AGREED)
                   ;
        });
        G::define('order-status-checked-agreed', function(User $user, Order $e) {
            return $e->isStatus(Order::STATUS_RECEIVED) ||
                   $e->isStatus(Order::STATUS_CHECKED_NOT_AGREED) ||
                   $e->isStatus(Order::STATUS_CHECKED_PARTIAL_AGREED)
                   ;
        });
        G::define('order-status-checked-invoiced', function(User $user, Order $e) {
            return $e->isStatus(Order::STATUS_CHECKED_AGREED);
        });
        G::define('order-status-paid', function(User $user, Order $e) {
            return $e->isStatus(Order::STATUS_CHECKED_INVOICED);
        });
    }
}
