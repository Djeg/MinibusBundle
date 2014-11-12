<?php

namespace Knp\MinibusBundle\Routing\Mapper;

use Symfony\Component\Routing\Route;

/**
 * In order to make a simple object, and because the MinibusRoutingLoader 
 * will became such a huge thing ! This is some route mapper that parse and
 * map a part of a route.
 *
 * @see \Knp\MinibusBundle\Routing\MinibusRoutingLoader
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
interface RouteMapper
{
    /**
     * @param mixed $routeName
     * @param array $attributes
     * @param Route $route
     */
    public function map($routeName, array $attributes, Route $route);
}
