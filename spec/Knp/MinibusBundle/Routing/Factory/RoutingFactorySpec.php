<?php

namespace spec\Knp\MinibusBundle\Routing\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Routing\Route;

class RoutingFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Routing\Factory\RoutingFactory');
    }

    function it_create_a_route_colection()
    {
        $this->createCollection()->shouldHaveType('Symfony\Component\Routing\RouteCollection');
    }

    function it_create_a_route()
    {
        $this->createRoute('/path')->shouldReturnRouteWith('/path');
    }

    function getMatchers()
    {
        return [
            'returnRouteWith' => function ($route, $path) {
                return $route instanceof Route && $route->getPath() === $path;
            }
        ];
    }
}
