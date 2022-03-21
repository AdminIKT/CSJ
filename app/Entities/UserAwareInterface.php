<?php

namespace App\Entities;

interface UserAwareInterface
{
    /**
     * Set user.
     *
     * @param User $user
     * @return UserAwareInterface 
     */
    public function setUser(User $user);

    /**
     * Get user.
     *
     * @return User 
     */
    public function getUser();
}
