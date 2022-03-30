<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\OrderEvent,
    App\Events\SupplierEvent,
    App\Events\AssignmentEvent,
    App\Events\MovementEvent;
use App\Listeners\Users,
    App\Listeners\Areas,
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
        ],
        SupplierEvent::class => [
            Users\EntityInjection::class,
        ],
        AssignmentEvent::class => [
            Areas\IncreaseCredit::class,
        ],
        MovementEvent::class => [
            Orders\UpdateStatus::class,
            Areas\RestoreCredit::class,
            Suppliers\IncreaseInvoiced::class,
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
