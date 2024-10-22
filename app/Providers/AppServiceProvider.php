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
                if ($user->isAdmin()) return 1;
            }
            return 0;
        });

        //Support for JSON_ARRAY type in DBAL v3
        //if (!\Doctrine\DBAL\Types\Type::hasType('json_array')) {
        //    \Doctrine\DBAL\Types\Type::addType('json_array', \Doctrine\DBAL\Types\JsonType::class);
        //}

        //$platform = $this->app['db']->getDoctrineConnection()->getDatabasePlatform();
        //$platform->markDoctrineTypeCommented(\Doctrine\DBAL\Types\Type::getType('json_array'));

    }
}
