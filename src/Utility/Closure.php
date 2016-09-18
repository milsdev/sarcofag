<?php
/**
 * Sarcofag (http://sarcofag.com)
 *
 * @link       https://github.com/milsdev/sarcofag
 * @copyright  Copyright (c) 20012-2016 Mil's (http://www.mils.agency)
 * @license    http://sarcofag.com/license/mit
 */

namespace Sarcofag\Utility;

/**
 * Closure class is a decorator for the
 * \Closure from PHP Core, to have in future
 * abilities to Mock the Closure, because base
 * Closure class defined as a FINAL, so can't
 * mock it.
 */
class Closure
{
    /**
     * @var \Closure
     */
    protected $closure;

    /**
     * Closure constructor.
     *
     * @param \Closure $callable Closure object which will be called.
     */
    public function __construct(\Closure $callable)
    {
        $this->closure = $closure;
    }

    /**
     * It is just a wrapper for the
     *
     * @link http://www.php.net/manual/en/closure.bindto.php
     * @param object $newthis The object to which the given anonymous function should be bound, or NULL for the closure to be unbound.
     * @param mixed $newscope The class scope to which associate the closure is to be associated, or 'static' to keep the current one.
     * If an object is given, the type of the object will be used instead.
     * This determines the visibility of protected and private methods of the bound object.
     * @return Closure Returns the newly created Closure object or FALSE on failure
     */
    public function bindTo($newthis, $newscope = 'static')
    {
        return $this->closure->bindTo($newthis, $newscope);
    }
}
