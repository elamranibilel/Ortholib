<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSomething(): void
    {
        $user = new User();
        $user->setNom('Doe')
            ->setPrenom('John')
            ->setEmail('elamranibilel@gmail.com')
            ->setTelephone('123456789')
            ->setPassword('password')
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setGenre('Masculin')
            ->setVille('Paris');

        /* $this->assertTrue($user->getNom() === 'Doe');
        $this->assertTrue($user->getPrenom() === 'John');
        $this->assertTrue($user->getEmail() === 'elamranibilel@gmail.com');
        $this->assertTrue($user->getTelephone() === '123456789');
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getRoles() === ['ROLE_USER']);
        $this->assertTrue($user->getCreatedAt() instanceof \DateTimeImmutable);
        $this->assertTrue($user->getGenre() === 'Masculin');
        $this->assertTrue($user->getVille() === 'Paris'); */

        $this->assertSame('Doe', $user->getNom());
        $this->assertSame('John', $user->getPrenom());
        $this->assertSame('elamranibilel@gmail.com', $user->getEmail());
        $this->assertSame('elamranibilel@gmail.com', $user->getUserIdentifier());
        $this->assertSame('123456789', $user->getTelephone());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame(['ROLE_USER'], $user->getRoles());
        /* Dans le constructeur de la classe User, on initialise le champ createdAt avec une nouvelle instance de DateTimeImmutable
        donc ce champ ne sera jamais vide */
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
        $this->assertSame('Masculin', $user->getGenre());
        $this->assertSame('Paris', $user->getVille());
    }

    public function testNoSomething(): void
    {
        $user = new User();
        $user->setNom('Paul')
            ->setPrenom('Pascal')
            ->setEmail('elamranibilel2@gmail.com')
            ->setTelephone('123456789')
            ->setPassword('password')
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setGenre('Feminin')
            ->setVille('Marseille');

        $this->assertFalse($user->getNom() === 'Doe');
        $this->assertFalse($user->getPrenom() === 'John');
        $this->assertFalse($user->getEmail() === 'elamranibilel@gmail.com');
        $this->assertTrue($user->getTelephone() === '123456789');
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getRoles() === ['ROLE_USER']);
        $this->assertTrue($user->getCreatedAt() instanceof \DateTimeImmutable);
        $this->assertFalse($user->getGenre() === 'Masculin');
        $this->assertFalse($user->getVille() === 'Paris');
    }

    public function testToString(): void
    {
        $user = new User();
        $user->setNom('Doe');
        $this->assertSame('Doe', (string) $user);
    }

    public function testRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    public function testEmptyUser(): void
    {
        $user = new User();

        $this->assertEmpty($user->getId());
        $this->assertEmpty($user->getNom());
        $this->assertEmpty($user->getPrenom());
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getTelephone());
        $this->assertEmpty($user->getPassword());
        $this->assertSame([], $user->getRoles());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
        $this->assertEmpty($user->getGenre());
        $this->assertEmpty($user->getVille());
    }
}
