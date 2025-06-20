<?php

use App\Entities\User;
use App\Entities\Role;
use Illuminate\Support\Facades\Hash;
use Doctrine\ORM\EntityManagerInterface;

if (!function_exists('createUserWithRole')) {
    function createUserWithRole(int $roleId, string $email = null, string $name = null): User {
        $em = app(EntityManagerInterface::class);
        $role = $em->getRepository(Role::class)->find($roleId);
        
        if (!$role) {
            throw new \InvalidArgumentException("Role with ID {$roleId} not found");
        }
        
        $user = new User();
        $user->setEmail($email ?: "user{$roleId}@test.com")
             ->setName($name ?: 'Test User')
             ->setPassword(Hash::make('password123'))
             ->addRole($role);
        
        $em->persist($user);
        $em->flush();
        
        return $user;
    }
}

if (!function_exists('createAdminUser')) {
    function createAdminUser(string $email = 'admin@test.com', string $name = 'Admin User'): User {
        return createUserWithRole(Role::ROLE_ADMIN, $email, $name);
    }
}

if (!function_exists('createSalesUser')) {
    function createSalesUser(string $email = null, string $name = null): User {
        return createUserWithRole(Role::ROLE_SALES, $email, $name);
    }
}

if (!function_exists('createReceptionUser')) {
    function createReceptionUser(string $email = null, string $name = null): User {
        return createUserWithRole(Role::ROLE_RECEPTION, $email, $name);
    }
}
