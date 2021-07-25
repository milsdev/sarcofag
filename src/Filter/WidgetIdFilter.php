<?php
namespace Sarcofag\Filter;


use Laminas\Filter\AbstractFilter;
use Laminas\Filter\Exception;

class WidgetIdFilter extends AbstractFilter
{
    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     *
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        if (!is_scalar($value) && !is_array($value)) {
            return $value;
        }
        // Use native language alphabet
        $pattern = '/[^a-zA-Z0-9\_\-]/i';
        return preg_replace($pattern, '', $value);
    }
}
