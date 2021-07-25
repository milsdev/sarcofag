<?php
namespace SarcofagTest\Service\SPI\EventManager;

use DI\Container;
use Sarcofag\Exception\RuntimeException;
use Sarcofag\API\WP;
use Sarcofag\SPI\EventManager\Action\ActionInterface;
use Sarcofag\SPI\EventManager\DataFilter\DataFilterInterface;
use Sarcofag\SPI\EventManager\EventManager;
use Sarcofag\SPI\EventManager\ListenerFactory;
use Sarcofag\SPI\EventManager\ListenerInterface;

class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WP
     */
    protected $wpStub;

    public function setUp()
    {
        $this->wpStub = $this->getMockBuilder(WP::class)->getMock();
    }

    /**
     * @param string[] $names
     * @param callable $defaultHandler
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getListeners(array $names)
    {
        $event = $this->getMockBuilder(ListenerInterface::class)->getMock();

        $event->expects($this->once())->method('getNames')->willReturn($names);
        $event->expects($this->exactly(count($names)))->method('getPriority')
                ->willReturn(96);
        $event->expects($this->exactly(count($names)))->method('getCallable')
                ->willReturn($event);
        $event->expects($this->exactly(count($names)))->method('getArgc')
                ->willReturn(2);
        return $event;
    }

    /**
     * Test if attaching action listeners
     * will initiate registering them inside the
     * wordpress actions by add_action procedure
     */
    public function testAttachActionListeners()
    {
        $actionListener = $this->getMockBuilder(ActionInterface::class)->getMock();

        $listenersFirst = $this->getListeners(['t1','t2']);
        $listenersSecond = $this->getListeners(['t1','t2']);

        $actionListener->expects($this->once())
                       ->method('getActionListeners')
                       ->willReturn([$listenersFirst, $listenersSecond]);

        $listenerFactory = $this->getMockBuilder(ListenerFactory::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $listenerFactory->expects($this->exactly(2))
                        ->method('makeListener')
                        ->willReturnArgument(1);

        $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

        $this->wpStub
             ->expects($this->exactly(4))
             ->method('__call')
             ->withConsecutive([$this->equalTo('add_action'), $this->equalTo(['t1', $listenersFirst, 96, 2])],
                               [$this->equalTo('add_action'), $this->equalTo(['t2', $listenersFirst, 96, 2])],
                               [$this->equalTo('add_action'), $this->equalTo(['t1', $listenersSecond, 96, 2])],
                               [$this->equalTo('add_action'), $this->equalTo(['t2', $listenersSecond, 96, 2])]);

        $eventManager = new EventManager(
            $this->wpStub,
            $container,
            $listenerFactory
        );

        $eventManager->attachListeners($actionListener);
    }

    /**
     * Test if attaching data filter listeners
     * will initiate registering them inside the
     * wordpress filters by add_filter procedure
     */
    public function testAttachDataFilterListeners()
    {
        $actionListener = $this->getMockBuilder(DataFilterInterface::class)->getMock();

        $listenersFirst = $this->getListeners(['t1','t2']);
        $listenersSecond = $this->getListeners(['t1','t2']);

        $actionListener->expects($this->once())
                       ->method('getDataFilterListeners')
                       ->willReturn([$listenersFirst, $listenersSecond]);

        $listenerFactory = $this->getMockBuilder(ListenerFactory::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $listenerFactory->expects($this->exactly(2))
                        ->method('makeListener')
                        ->willReturnArgument(1);

        $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

        $this->wpStub
             ->expects($this->exactly(4))
             ->method('__call')
             ->withConsecutive([$this->equalTo('add_filter'), $this->equalTo(['t1', $listenersFirst, 96, 2])],
                               [$this->equalTo('add_filter'), $this->equalTo(['t2', $listenersFirst, 96, 2])],
                               [$this->equalTo('add_filter'), $this->equalTo(['t1', $listenersSecond, 96, 2])],
                               [$this->equalTo('add_filter'), $this->equalTo(['t2', $listenersSecond, 96, 2])]);

        $eventManager = new EventManager(
            $this->wpStub,
            $container,
            $listenerFactory
        );
        $eventManager->attachListeners($actionListener);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testExceptionInAttachListeners()
    {
        $listenerFactory = $this->getMockBuilder(ListenerFactory::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $listenerFactory->expects($this->exactly(0))
                        ->method('makeListener')
                        ->willReturnArgument(1);

        $container = $this->getMockBuilder(Container::class)->disableOriginalConstructor()->getMock();

        $eventManager = new EventManager($this->wpStub, $container, $listenerFactory);
        $eventManager->attachListeners(function (){});
    }
}
