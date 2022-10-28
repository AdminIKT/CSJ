<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\OrderEvent,
    App\Events\SupplierEvent,
    App\Events\IncidenceEvent,
    App\Events\MovementEvent;

use App\Listeners\Users,
    App\Listeners\Accounts,
    App\Listeners\Orders,
    App\Listeners\Suppliers
    ;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \Illuminate\Auth\Events\Login::class => [
            Users\LogSuccessfullLogin::class,
        ],
        OrderEvent::class => [
            Users\EntityInjection::class,
            Orders\ActionInjection::class,
        ],
        SupplierEvent::class => [
            Users\EntityInjection::class,
        ],
        IncidenceEvent::class => [
            Users\EntityInjection::class,
            Suppliers\AcceptableSupplier::class,
        ],
        MovementEvent::class => [
            Orders\UpdateStatus::class,
            Accounts\RestoreCredit::class,
            Suppliers\IncreaseInvoiced::class,
            Suppliers\RecommendableSupplier::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
