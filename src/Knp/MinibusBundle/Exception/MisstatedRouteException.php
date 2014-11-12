<?php

namespace Knp\MinibusBundle\Exception;

/**
 * This exception happen during a parsing of a route that has been misstated.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class MisstatedRouteException extends \Exception
{
    /**
     * @param mixed      $routeName
     * @param \Exception $reason
     */
    public function __construct($routeName, \Exception $reason)
    {
        parent::__construct(sprintf(
            'The route "%s" has been misstated :-(',
            $routeName
        ), 0, $reason);
    }
}
