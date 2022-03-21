<?php

namespace App\Events;

use App\Entities\UserAwareInterface;

interface UserAwareEvent
{
    /**
     * @return UserAwareInterface
     */
    public function getUserAwareEntity();
}
