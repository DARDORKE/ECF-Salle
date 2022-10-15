<?php

namespace App\Tests;

use App\Entity\Partner;
use PHPUnit\Framework\TestCase;

class PartnerTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new Partner();

        $user->setName('true');


        $this->assertTrue($user->getName() === 'true');

    }

    public function testIsFalse(): void
    {
        $user = new Partner();

        $user->setName('true');


        $this->assertFalse($user->getName() === 'false');

    }

    public function testIsEmpty(): void
    {
        $user = new Partner();

        $this->assertEmpty($user->getName());
        $this->assertEmpty($user->getUser());
        $this->assertEmpty($user->getStructures());

    }
}
