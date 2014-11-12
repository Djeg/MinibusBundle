<?php

namespace spec\Knp\MinibusBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Definition;
use Knp\MinibusBundle\DependencyInjection\ReferenceFactory;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterStationPassSpec extends ObjectBehavior
{
    function let(ReferenceFactory $referenceFactory)
    {
        $this->beConstructedWith($referenceFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\Compiler\RegisterStationPass');
    }

    function it_is_a_compiler_pass()
    {
        $this->shouldHaveType('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
    }

    function it_only_collect_station_if_the_registry_is_defined(ContainerBuilder $container)
    {
        $container->hasDefinition('knp_minibus.station_registry')->willReturn(false);
        $container->getDefinition('knp_minibus.station_registry')->shouldNotBeCalled();

        $this->process($container);
    }

    function it_add_all_the_tagged_station_to_the_station_registry_definition(
        ContainerBuilder $container,
        Definition $definition,
        $referenceFactory,
        Reference $reference
    ) {
        $container->hasDefinition('knp_minibus.station_registry')->willReturn(true);
        $container->getDefinition('knp_minibus.station_registry')->willReturn($definition);

        $container->findTaggedServiceIds('knp_minibus.station')->willReturn([
            'some.station' => [
                ['name' => 'some_station'],
            ]
        ]);

        $referenceFactory = $referenceFactory->create('some.station')->willReturn($reference);

        $definition->addMethodCall('collect', [
            $reference,
            'some_station'
        ])->shouldBeCalled();

        $this->process($container);
    }

    function it_throw_an_exception_when_a_tagged_station_has_no_name(
        ContainerBuilder $container,
        Definition $definition,
        $referenceFactory,
        Reference $reference
    ) {
        $container->hasDefinition('knp_minibus.station_registry')->willReturn(true);
        $container->getDefinition('knp_minibus.station_registry')->willReturn($definition);

        $container->findTaggedServiceIds('knp_minibus.station')->willReturn([
            'some.station' => []
        ]);

        $this
            ->shouldThrow('Knp\MinibusBundle\Exception\UndefinedStationNameException')
            ->duringProcess($container)
        ;
    }
}
