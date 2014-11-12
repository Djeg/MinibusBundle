<?php

namespace Knp\MinibusBundle\Routing\Mapper;

use Symfony\Component\Routing\Route;

/**
 * Map the route requirements.
 *
 * @author David Jegat <david.jegat@gmail.com>
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
