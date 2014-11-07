<?php

namespace spec\Knp\MinibusBundle\Bundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\MinibusBundle\DependencyInjection\Compiler\PassFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class MinibusBundleSpec extends ObjectBehavior
{
    function let(PassFactory $passFactory)
    {
        $this->beConstructedWith($passFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Bundle\MinibusBundle');
    }

    function it_is_a_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }

    function it_add_compiler_pass_during_the_build(
        ContainerBuilder $container,
        $passFactory,
        CompilerPassInterface $pass
    ) {
        $passFactory->createAutoStationRegistration($this)->willReturn($pass);
        $container->addCompilerPass($pass)->shouldBeCalled();

        $this->build($container);
    }
}
