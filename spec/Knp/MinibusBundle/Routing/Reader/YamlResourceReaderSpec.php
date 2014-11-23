<?php

namespace spec\Knp\MinibusBundle\Routing\Reader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\MinibusBundle\Path\BundlePathResolver;
use Knp\MinibusBundle\Yaml\YamlParser;

class YamlResourceReaderSpec extends ObjectBehavior
{
    function let(
        BundlePathResolver $resolver,
        YamlParser $parser
    ) {
        $this->beConstructedWith($resolver, $parser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Routing\Reader\YamlResourceReader');
    }

    function it_read_a_minibus_yaml_routing_ressource($resolver, $parser)
    {
        $rawRoutes = [
            'route_name' => [
                'pattern'  => '/some/pattern',
                'line'     => ['station'    => 'config'],
                'terminus' => ['a terminus' => ['some configuration']],
            ]
        ];

        $resolver->getPath('@SomeBundle/Resources/routing.yml')->willReturn('/path/to/routing.yml');
        $parser->parse('/path/to/routing.yml')->willReturn($rawRoutes);

        $this->read('@SomeBundle/Resources/routing.yml')->shouldReturn([
            'route_name' => [
                'method'       => 'GET',
                'format'       => 'html',
                'condition'    => null,
                'host'         => null,
                'scheme'       => null,
                'defaults'     => [],
                'requirements' => [],
                'passengers'   => [],
                'pattern'      => '/some/pattern',
                'line'         => ['station'    => ['config']],
                'terminus'     => ['a terminus' => ['some configuration']],
            ]
        ]);
    }

    function it_throw_an_exception_if_a_route_is_misstated($resolver, $parser)
    {
        $rawRoutes = [
            'route_name' => [
                'pattern'     => '/some/pattern',
                'line'        => ['some station here'],
                'terminus'    => ['a terminus here'],
                'invalid_key' => 'plop ?'
            ]
        ];

        $resolver->getPath('@SomeBundle/Resources/routing.yml')->willReturn('/path/to/routing.yml');
        $parser->parse('/path/to/routing.yml')->willReturn($rawRoutes);

        $this
            ->shouldThrow('Knp\MinibusBundle\Exception\MisstatedRouteException')
            ->duringRead('@SomeBundle/Resources/routing.yml')
        ;
    }
}
