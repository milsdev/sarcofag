<?php
/**
 * Sarcofag (http://sarcofag.com)
 *
 * @link       https://github.com/milsdev/sarcofag
 * @copyright  Copyright (c) 20012-2016 Mil's (http://www.mils.agency)
 * @license    http://sarcofag.com/license/mit
 */
namespace SarcofagTest\Utility;
use Sarcofag\Utility\Closure;

/**
 * Test suite for testing Closure structure
 *
 * @covers \Sarcofag\Utility\Closure
 */
class ClosureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testIsClosureInstantiable()
    {
        $closure = new Closure(function ($value) {
            $this->assertTrue($value);
        });

        $func = $closure->bindTo($this);
        $func(true);
    }
}
