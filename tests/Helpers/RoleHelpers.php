<?php

use App\Entities\Role;
use Doctrine\ORM\EntityManagerInterface;

if (!function_exists('createAllRoles')) {
    function createAllRoles(): void {
        $em = app(EntityManagerInterface::class);
        
        $roles = [
            [Role::ROLE_ADMIN, 'Admin'],
            [Role::ROLE_SALES, 'Sales'],
            [Role::ROLE_RECEPTION, 'Reception']
        ];
        
        foreach ($roles as [$id, $name]) {
            // Usar inserción directa o crear entidades
            $em->getConnection()->executeStatement(
                'INSERT INTO user_roles (id, name) VALUES (?, ?)', 
                [$id, $name]
            );
        }
    }
}

if (!function_exists('getRoleById')) {
    function getRoleById(int $roleId): ?Role {
        $em = app(EntityManagerInterface::class);
        return $em->getRepository(Role::class)->find($roleId);
    }
}
