<?php

namespace spec\Knp\MinibusBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\MinibusBundle\DependencyInjection\ReferenceFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

class RegisterTerminusPassSpec extends ObjectBehavior
{
    function let(ReferenceFactory $factory)
    {
        $this->beConstructedWith($factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\Compiler\RegisterTerminusPass');
    }

    function it_is_a_compiler_pass()
    {
        $this->shouldHaveType('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface');
    }

    function it_does_not_process_if_the_terminus_registry_definition_does_not_exists(
        ContainerBuilder $container
    ) {
        $container->hasDefinition('knp_minibus.terminus_registry')->willReturn(false);
        $container->getDefinition('knp_minibus.terminus_registry')->shouldNotBeCalled();

        $this->process($container);
    }

    function it_throw_exception_on_mistatted_terminus_tag(
        ContainerBuilder $container,
        Definition $definition,
        $factory
    ) {
        $container->hasDefinition('knp_minibus.terminus_registry')->willReturn(true);
        $container->getDefinition('knp_minibus.terminus_registry')->willReturn($definition);

        $container->findTaggedServiceIds('knp_minibus.terminus')->willReturn([
            'terminus_service' => [],
        ]);

        $this
            ->shouldThrow('Knp\MinibusBundle\Exception\UndefinedTerminusNameException')
            ->duringProcess($container)
        ;
    }

    function it_add_tagged_terminus_to_the_registry(
        ContainerBuilder $container,
        Definition $definition,
        $factory,
        Reference $reference
    ) {
        $container->hasDefinition('knp_minibus.terminus_registry')->willReturn(true);
        $container->getDefinition('knp_minibus.terminus_registry')->willReturn($definition);

        $container->findTaggedServiceIds('knp_minibus.terminus')->willReturn([
            'terminus_service' => [['alias' => 'some terminus']],
        ]);

        $factory->create('terminus_service')->willReturn($reference);

        $definition->addMethodCall('collect', [$reference, 'some terminus'])->shouldBeCalled();

        $this->process($container);
    }
}
