<?php

namespace spec\Knp\MinibusBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\MinibusBundle\Finder\ClassFinder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Knp\MinibusBundle\DependencyInjection\DefinitionFactory;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Definition;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AutoRegisterStationPassSpec extends ObjectBehavior
{
    function let(Bundle $bundle, ClassFinder $classFinder, DefinitionFactory $definitionFactory)
    {
        $this->beConstructedWith($bundle, $classFinder, $definitionFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\Compiler\AutoRegisterStationPass');
    }

    function it_is_a_compiler_pass()
    {
        $this->shouldHaveType('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
    }

    function it_register_all_the_station_in_the_station_bundle_namespace(
        $bundle,
        $classFinder,
        $definitionFactory,
        ContainerBuilder $container,
        ReflectionClass $stationReflectionOne,
        ReflectionClass $stationReflectionTwo,
        ExtensionInterface $extension,
        Definition $firstDefinition,
        Definition $secondDefinition
    ) {
        $container->hasParameter('knp_minibus.disable_station_auto_registration')->willReturn(false);
        $bundle->getPath()->willReturn('/Some/Bundle');
        $bundle->getNamespace()->willReturn('Some\\Bundle');
        $bundle->getContainerExtension()->willReturn($extension);
        $classFinder
            ->findImplementation('Knp\Minibus\Station', '/Some/Bundle/Station', 'Some\\Bundle\\Station')
            ->willReturn([$stationReflectionOne, $stationReflectionTwo])
        ;
        $stationReflectionOne->getName()->willReturn('Some\\Bundle\\Station\\MyFirstStation');
        $stationReflectionOne->getShortName()->willReturn('MyFirstStation');
        $stationReflectionOne
            ->implementsInterface('Knp\MinibusBundle\Station\ContainerAwareStation')
            ->willReturn(false)
        ;
        $stationReflectionTwo->getName()->willReturn('Some\\Bundle\\Station\\Sub\\MySecondStation');
        $stationReflectionTwo->getShortName()->willReturn('MySecondStation');
        $stationReflectionTwo
            ->implementsInterface('Knp\MinibusBundle\Station\ContainerAwareStation')
            ->willReturn(true)
        ;
        $extension->getAlias()->willReturn('some_bundle');

        $container->hasDefinition('some_bundle.station.my_first')->willReturn(false);
        $container->hasDefinition('some_bundle.station.sub.my_second')->willReturn(false);

        $definitionFactory->create('Some\\Bundle\\Station\\MyFirstStation')->willReturn($firstDefinition);
        $definitionFactory->create('Some\\Bundle\\Station\\Sub\\MySecondStation')->willReturn($secondDefinition);

        $firstDefinition->addTag('knp_minibus.station', ['alias' => 'some_bundle.my_first'])->shouldBeCalled();
        $secondDefinition->addTag('knp_minibus.station', ['alias' => 'some_bundle.sub.my_second'])->shouldBeCalled();

        $secondDefinition->addMethodCall('setContainer', Argument::that(function ($args) {
            if (!is_array($args)) {
                return false;
            }

            if (!$args[0] instanceof Reference) {
                return false;
            }

            return 'container' === (string)$args[0];
        }))->shouldBeCalled();

        $container->setDefinition('some_bundle.station.my_first', $firstDefinition)->shouldBeCalled();
        $container->setDefinition('some_bundle.station.sub.my_second', $secondDefinition)->shouldBeCalled();

        $this->process($container);
    }

    function it_does_nothing_if_the_container_contains_a_the_special_parameters(ContainerBuilder $container, $classFinder)
    {
        $container->hasParameter('knp_minibus.disable_station_auto_registration')->willReturn(true);
        $classFinder->findImplementation(Argument::cetera())->shouldNotBeCalled();

        $this->process($container);
    }
}
