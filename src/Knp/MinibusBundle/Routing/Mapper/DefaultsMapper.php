<?php

namespace Knp\MinibusBundle\Routing\Mapper;

use Symfony\Component\Routing\Route;

/**
 * Mapp the route defaults attributes.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class DefaultsMapper implements RouteMapper
{
    /**
     * {@inheritdoc}
     */
    public function map($routeName, array $attributes, Route $route)
    {
        $defaults = [
            '_line'       => $attributes['line'],
            '_terminus'   => $attributes['terminus'],
            '_format'     => $attributes['format'],
            '_passengers' => $attributes['passengers'],
            '_controller' => 'knp_minibus.line.http_line_launcher:launch'
        ];

        $route->setDefaults(array_merge($attributes['defaults'], $defaults));
    }
}
