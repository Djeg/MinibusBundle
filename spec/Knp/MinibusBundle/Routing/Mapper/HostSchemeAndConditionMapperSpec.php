<?php

namespace spec\Knp\MinibusBundle\Routing\Mapper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Routing\Route;

class HostSchemeAndConditionMapperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Routing\Mapper\HostSchemeAndConditionMapper');
    }

    function it_is_a_mapper()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Routing\Mapper\RouteMapper');
    }

    function it_map_host_scheme_and_condition_to_the_route(Route $route)
    {
        $route->setCondition('some condition')->shouldBeCalled();
        $route->setHost('some host')->shouldBeCalled();
        $route->setSchemes('some schemes')->shouldBeCalled();

        $this->map('some_route', [
            'condition' => 'some condition',
            'host'      => 'some host',
            'scheme'    => 'some schemes'
        ], $route);
    }
}
