<?php

namespace spec\Knp\MinibusBundle\Routing\Mapper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Routing\Route;

class DefaultsMapperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Routing\Mapper\DefaultsMapper');
    }

    function it_is_a_route_mapper()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Routing\Mapper\RouteMapper');
    }

    function it_map_the_route_defaults(Route $route)
    {
        $route->setDefaults([
            '_controller' => 'knp_minibus.line.http_line_launcher:launch',
            '_line'       => ['some line'],
            '_terminus'   => ['some terminus'],
            '_format'     => 'html',
            '_passengers' => ['some' => 'passengers'],
            'foo'         => 'bar'
        ])->shouldBeCalled();

        $this->map('some_route', [
            'line' => ['some line'],
            'terminus' => ['some terminus'],
            'format' => 'html',
            'defaults' => [
                'foo' => 'bar'
            ],
            'passengers' => ['some' => 'passengers'],
        ], $route);
    }
}
