<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Administrateur;
use PHPUnit\Framework\TestCase;

class AdministrateurTest extends TestCase
{
    public function testIsInstanceOfUser(): void
    {
        $admin = new Administrateur();
        $this->assertInstanceOf(User::class, $admin);
    }

    public function testAdminHasRoleAdmin(): void
    {
        $admin = new Administrateur();
        $admin->setRoles([]);

        $roles = $admin->getRoles();

        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
    }

    public function testAdminKeepsCustomRoles(): void
    {
        $admin = new Administrateur();
        $admin->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_MANAGER']);

        $roles = $admin->getRoles();

        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_SUPER_ADMIN', $roles);
        $this->assertContains('ROLE_MANAGER', $roles);
        $this->assertContains('ROLE_USER', $roles);
        $this->assertCount(count(array_unique($roles)), $roles);
    }
}
