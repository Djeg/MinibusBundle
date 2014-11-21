<?php

namespace spec\Knp\MinibusBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\MinibusBundle\DependencyInjection\Compiler\CompilerPassFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

class KnpMinibusBundleSpec extends ObjectBehavior
{
    function let(CompilerPassFactory $factory)
    {
        $this->beConstructedWith($factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\KnpMinibusBundle');
    }

    function it_is_a_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }

    function it_register_station_registration_compiler_pass(
        $factory,
        CompilerPassInterface $pass1,
        CompilerPassInterface $pass2,
        ContainerBuilder $container
    ) {
        $factory->createStationRegistration()->willReturn($pass1);
        $factory->createTerminusRegistration()->willReturn($pass2);

        $container->addCompilerPass($pass1, PassConfig::TYPE_OPTIMIZE)->shouldBeCalled();
        $container->addCompilerPass($pass2, PassConfig::TYPE_OPTIMIZE)->shouldBeCalled();

        $this->build($container);
    }
}
