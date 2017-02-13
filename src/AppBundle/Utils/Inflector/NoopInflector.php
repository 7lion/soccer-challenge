<?php

namespace AppBundle\Utils\Inflector;

use FOS\RestBundle\Util\Inflector\InflectorInterface;

/**
 * NOT to pluralize rest routes!
 * Class NoopInflector
 * @package AppBundle\Utils
 */
class NoopInflector implements InflectorInterface
{
    public function pluralize($word)
    {
        // Don't pluralize
        return $word;
    }
}
