<?php

namespace spec\Knp\MinibusBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Definition;
use Knp\MinibusBundle\DependencyInjection\ReferenceFactory;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StationPassSpec extends ObjectBehavior
{
    function let(ReferenceFactory $referenceFactory)
    {
        $this->beConstructedWith($referenceFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\Compiler\StationPass');
    }

    function it_is_a_compiler_pass()
    {
        $this->shouldHaveType('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
    }

    function it_only_collect_station_if_the_container_is_defined(ContainerBuilder $container)
    {
        $container->hasDefinition('knp_minibus.station.named_station_container')->willReturn(false);
        $container->getDefinition('knp_minibus.station.named_station_container')->shouldNotBeCalled();

        $this->process($container);
    }

    function it_add_all_the_tagged_station_to_the_station_container_definition(
        ContainerBuilder $container,
        Definition $definition,
        $referenceFactory,
        Reference $reference
    ) {
        $container->hasDefinition('knp_minibus.station.named_station_container')->willReturn(true);
        $container->getDefinition('knp_minibus.station.named_station_container')->willReturn($definition);

        $container->findTaggedServiceIds('knp_minibus.station')->willReturn([
            'some.station' => [
                'alias' => 'station_alias'
            ]
        ]);

        $referenceFactory = $referenceFactory->create('some.station')->willReturn($reference);

        $definition->addMethodCall('add', [
            'station_alias',
            $reference
        ])->shouldBeCalled();

        $this->process($container);
    }

    function it_throw_an_exception_when_a_tagged_station_has_no_alias(
        ContainerBuilder $container,
        Definition $definition,
        $referenceFactory,
        Reference $reference
    ) {
        $container->hasDefinition('knp_minibus.station.named_station_container')->willReturn(true);
        $container->getDefinition('knp_minibus.station.named_station_container')->willReturn($definition);

        $container->findTaggedServiceIds('knp_minibus.station')->willReturn([
            'some.station' => []
        ]);

        $this
            ->shouldThrow('Knp\MinibusBundle\Exception\UndefinedStationAliasException')
            ->duringProcess($container)
        ;
    }
}
