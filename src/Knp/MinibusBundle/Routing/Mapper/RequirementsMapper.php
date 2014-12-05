<?php

namespace Knp\MinibusBundle\Routing\Mapper;

use Symfony\Component\Routing\Route;

/**
 * Map the route requirements.
 */
class RequirementsMapper implements RouteMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($routeName, array $attributes, Route $route)
    {
        $requirements = [
            '_method' => $attributes['method'],
            '_format' => $attributes['format'],
        ];

        $route->setRequirements(array_merge($attributes['requirements'], $requirements));
    }
}
