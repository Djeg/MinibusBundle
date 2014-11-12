<?php

namespace Knp\MinibusBundle\Routing\Factory;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

/**
 * Easily create route and route collection.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class RoutingFactory
{
    /**
     * @return RouteCollection
     */
    public function createCollection()
    {
        return new RouteCollection;
    }

    /**
     * @param string $path
     *
     * @return Route
     */
    public function createRoute($path = '/')
    {
        return new Route($path);
    }
}
