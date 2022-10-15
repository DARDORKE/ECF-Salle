<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new User();

        $user->setEmail('true@test.com')
            ->setRoles(["ROLE_STRUCTURE"])
            ->setPassword('true')
            ->setEnabled(true);


        $this->assertTrue($user->getEmail() === 'true@test.com');
        $this->assertTrue($user->getRoles() === ["ROLE_STRUCTURE"]);
        $this->assertTrue($user->getPassword() === 'true');
        $this->assertTrue($user->isEnabled() === true);

    }

    public function testIsFalse(): void
    {
        $user = new User();

        $user->setEmail('true@test.com')
            ->setPassword('true')
            ->setEnabled(true);


        $this->assertFalse($user->getEmail() === 'false@test.com');
        $this->assertFalse($user->getPassword() === 'false');
        $this->assertFalse($user->isEnabled() === false);

    }

    public function testIsEmpty(): void
    {
        $user = new User();

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->isEnabled());
        $this->assertEmpty($user->getStructures());
        $this->assertEmpty($user->getModules());
        $this->assertEmpty($user->getPartners());
    }
}
