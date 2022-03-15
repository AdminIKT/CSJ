<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('admin', function () {
            if (null !== ($user = auth()->user())) {
                foreach ($user->getRoles() as $role) {
                    if ($role->getName() === 'Admin') return 1;
                }
            }
            return 0;
        });
    }
}
