<?php

namespace App\Listeners\Users;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Doctrine\ORM\EntityManagerInterface;

class LogSuccessfullLogin
{
    /**
     * @EntityManagerInterface
     */ 
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Providers\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->setLastLogin(new \DateTime);
        $this->em->flush();
    }
}
