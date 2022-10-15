<?php

namespace App\Tests;

use App\Entity\Structure;
use PHPUnit\Framework\TestCase;

class StructureTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new Structure();

        $user->setAddress('test');


        $this->assertTrue($user->getAddress() === 'test');

    }

    public function testIsFalse(): void
    {
        $user = new Structure();

        $user->setAddress('true');


        $this->assertFalse($user->getAddress() === 'false');

    }

    public function testIsEmpty(): void
    {
        $user = new Structure();

        $this->assertEmpty($user->getAddress());
        $this->assertEmpty($user->getPartner());
        $this->assertEmpty($user->getUser());

    }
}
