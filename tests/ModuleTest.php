<?php

namespace App\Tests;

use App\Entity\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new Module();

        $user->setName('true');


        $this->assertTrue($user->getName() === 'true');

    }

    public function testIsFalse(): void
    {
        $user = new Module();

        $user->setName('true');


        $this->assertFalse($user->getName() === 'false');

    }

    public function testIsEmpty(): void
    {
        $user = new Module();

        $this->assertEmpty($user->getName());
        $this->assertEmpty($user->getUsers());

    }
}
