<?php

namespace spec\Knp\MinibusBundle\Resolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ArgumentResolverSpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Resolver\ArgumentResolver');
    }

    function it_is_a_resolver()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Resolver\Resolver');
    }

    function it_supports_any_kind_of_arguments(Request $request)
    {
        $this->supports('something', $request)->shouldReturn(true);
    }

    function it_return_as_is_a_non_string_subject(Request $request)
    {
        $this->resolve(['not a string'], $request)->shouldReturn(['not a string']);
    }

    function it_resolve_an_attribute(
        Request $request,
        ParameterBag $attributes
    ) {
        $request->attributes = $attributes;
        $attributes->get('id', null)->willReturn('the id value');

        $this->resolve('$id', $request)->shouldReturn('the id value');
    }

    function it_resolve_a_parameter(
        Request $request,
        $container
    ) {
        $container->getParameter('foo')->willReturn('parameter value');

        $this->resolve('%foo%', $request)->shouldReturn('parameter value');
    }

    function it_resolve_a_service(
        Request $request,
        $container
    ) {
        $container->get('some_service')->willReturn('a service');

        $this->resolve('@some_service', $request)->shouldReturn('a service');
    }
}
