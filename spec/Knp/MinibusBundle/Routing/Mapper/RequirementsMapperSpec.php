<?php

namespace spec\Knp\MinibusBundle\Routing\Mapper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Routing\Route;

class RequirementsMapperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Routing\Mapper\RequirementsMapper');
    }

    function it_is_a_route_mapper()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Routing\Mapper\RouteMapper');
    }

    function it_map_route_requirements(Route $route)
    {
        $route->setRequirements([
            '_format' => 'html',
            '_method' => 'GET',
            'foo'     => 'bar'
        ])->shouldBeCalled();

        $this->map('route_name', [
            'format' => 'html',
            'method' => 'GET',
            'requirements' => [
                'foo' => 'bar'
            ]
        ], $route);
    }
}
