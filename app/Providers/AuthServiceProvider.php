<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
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

        Gate::define('show-order', function (User $user, Order $e) {
            return $e->getArea()->getUsers()->contains($user);
        });
        Gate::define('update-order', function (User $user, Order $e) {
            return $e->getArea()->getUsers()->contains($user);
        });
        Gate::define('delete-order', function (User $user, Order $e) {
            return $e->isPending() && $e->getArea()->getUsers()->contains($user);
        });
    }
}
