<?php

namespace spec\Knp\MinibusBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Knp\MinibusBundle\Finder\ClassFinder;
use Knp\MinibusBundle\DependencyInjection\DefinitionFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AutoRegisterTerminusPassSpec extends ObjectBehavior
{
    function let(
        Bundle            $bundle,
        ClassFinder       $finder,
        DefinitionFactory $definitionFactory
    ) {
        $this->beConstructedWith($bundle, $finder, $definitionFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\Compiler\AutoRegisterTerminusPass');
    }

    function it_is_a_compiler_pass()
    {
        $this->shouldHaveType('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
    }

    function it_does_not_register_all_the_terminus_if_the_container_has_the_special_parameter(
        ContainerBuilder $container,
        $finder
    ) {
        $container->hasParameter('knp_minibus.disable_terminus_auto_registration')->willReturn(true);

        $finder->findImplementation(Argument::cetera())->shouldNotBeCalled();
    }

    function it_register_all_terminus_in_terminus_namespace(
        ContainerBuilder $container,
        $bundle,
        $finder,
        $definitionFactory,
        Definition $definition,
        \ReflectionClass $reflection
    ) {
        $bundle->getContainerExtension()->willReturn(null);
        $bundle->getNamespace()->willReturn('Vendor\\SomeBundle');
        $bundle->getPath()->willReturn('/Vendor/SomeBundle');

        $container->hasParameter('knp_minibus.disable_terminus_auto_registration')->willReturn(false);
        $finder->findImplementation(
            'Knp\\Minibus\\Terminus\\Terminus',
            '/Vendor/SomeBundle/Terminus',
            'Vendor\\SomeBundle\\Terminus'
        )->willReturn([$reflection]);

        $reflection->getName()->willReturn('Vendor\\SomeBundle\\Terminus\\SomeTerminus');

        $container->hasDefinition('vendor_some.terminus.some')->willReturn(false);

        $definitionFactory->create('Vendor\\SomeBundle\\Terminus\\SomeTerminus')->willReturn($definition);

        $definition->addTag('knp_minibus.terminus', [
            'alias' => 'vendor_some.some'
        ]);

        $container->setDefinition('vendor_some.terminus.some', $definition)->shouldBeCalled();

        $this->process($container);
    }
}
