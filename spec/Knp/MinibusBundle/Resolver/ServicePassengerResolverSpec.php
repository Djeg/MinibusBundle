<?php

namespace spec\Knp\MinibusBundle\Resolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\MinibusBundle\Resolver\ArgumentResolver;
use Knp\MinibusBundle\Minibus\MinibusFactory;

class ServicePassengerResolverSpec extends ObjectBehavior
{
    function let(ContainerInterface $container, ArgumentResolver $resolver)
    {
        $this->beConstructedWith($container, $resolver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Resolver\ServicePassengerResolver');
    }

    function it_is_a_resolver()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Resolver\Resolver');
    }

    function it_only_supports_array_with_service_key(Request $request)
    {
        $this->supports([], $request)->shouldReturn(false);
        $this->supports('', $request)->shouldReturn(false);
        $this->supports(['foo' => 'bar'], $request)->shouldReturn(false);
        $this->supports(['service' => 'bar'], $request)->shouldReturn(true);
    }

    function it_resolve_a_service(Request $request, $container)
    {
        $container->get('plop')->willReturn('a service');

        $this->resolve(['service' => 'plop'], $request)->shouldReturn('a service');
    }

    function it_resolve_service_method_and_arguements( 
        Request $request,
        $container,
        $resolver
    ) {
        $service = new MinibusFactory;
        $container->get('some_service')->willReturn($service);

        $resolver->resolve('@request', $request)->willReturn($request);

        $this->resolve([
            'service' => 'some_service',
            'method'  => 'createHttpMinibus',
            'arguments' => ['@request']
        ], $request)->shouldHaveType('Knp\Minibus\Minibus\HttpMinibus');
    }
}
