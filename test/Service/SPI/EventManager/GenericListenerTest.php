<?php
namespace SarcofagTest\Service\SPI\EventManager;

use Sarcofag\SPI\EventManager\GenericListener;

class GenericListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorWithArguments()
    {
        $names = ['some', 'tam'];
        $callable = $this->getMock(\stdClass::class, ['__invoke']);
        $callable->expects($this->once())->method('__invoke');

        $priority = 4;
        $argc = 2;
        
        $listener = new GenericListener($names, function () use ($callable){ $callable();}, $priority, $argc);
        $this->assertEquals($names, $listener->getNames());
        $this->assertEquals($priority, $listener->getPriority());
        $this->assertEquals($argc, $listener->getArgc());

        $listener();
    }

    public function testConstructorWithDefaultArguments()
    {
        $names = ['some', 'tam'];
        $callable = $this->getMock(\stdClass::class, ['__invoke']);
        $callable->expects($this->once())->method('__invoke');

        $listener = new GenericListener($names, function () use ($callable){ $callable();});
        $this->assertEquals($names, $listener->getNames());
        $this->assertNull($listener->getPriority());
        $this->assertEquals(1, $listener->getArgc());

        $listener();
    }
}
