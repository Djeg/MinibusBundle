<?php

namespace spec\Knp\MinibusBundle\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\MinibusBundle\Routing\Reader\YamlResourceReader;
use Knp\MinibusBundle\Routing\Factory\RoutingFactory;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Knp\MinibusBundle\Routing\Mapper\RouteMapper;

class MinibusRoutingLoaderSpec extends ObjectBehavior
{
    function let(YamlResourceReader $resourceReader, RoutingFactory $routingFactory)
    {
        $this->beConstructedWith($resourceReader, $routingFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Routing\MinibusRoutingLoader');
    }

    function it_is_a_routing_loader()
    {
        $this->shouldHaveType('Symfony\Component\Config\Loader\LoaderInterface');
    }

    function it_supports_minibus_yaml_routing_type()
    {
        $this->supports('all.yaml', 'minibus')->shouldReturn(true);
        $this->supports('all.yml', 'minibus')->shouldReturn(true);

        $this->supports('all.xml', 'minibus')->shouldReturn(false);
    }

    function it_load_and_transform_a_resource_into_collection(
        $resourceReader,
        $routingFactory,
        RouteCollection $collection,
        Route $route,
        RouteMapper $mapper
    ) {
        $resourceReader->read('@SomeBundle/Resources/routing.yml')->willReturn([
            'route_name' => [
                'pattern' => '/some/pattern'
            ]
        ]);

        $routingFactory->createCollection()->willReturn($collection);
        $routingFactory->createRoute('/some/pattern')->willReturn($route);

        $mapper->map('route_name', ['pattern' => '/some/pattern'], $route)->shouldBeCalled();

        $collection->add('route_name', $route)->shouldBeCalled();

        $this->addMapper($mapper);

        $this->load('@SomeBundle/Resources/routing.yml')->shouldReturn($collection);
    }
}
