<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Factory\TasksFactory;

class TasksTest extends TestCase
{
    public function testSomething(): void
    {
        
        $t=TasksFactory::createOne(['name' => 'My Name']);
        $this->assertEquals("My Name", $t->getName());

        //$this->assertTrue(true);
    }
}
