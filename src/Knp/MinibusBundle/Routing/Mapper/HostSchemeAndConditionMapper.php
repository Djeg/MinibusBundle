<?php

namespace Knp\MinibusBundle\Routing\Mapper;

use Symfony\Component\Routing\Route;

/**
 * Map host, scheme and condition to the route.
 */
class HostSchemeAndConditionMapper implements RouteMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($routeName, array $attributes, Route $route)
    {
        $route->setSchemes($attributes['scheme']);
        $route->setHost($attributes['host']);
        $route->setCondition($attributes['condition']);
    }
}
