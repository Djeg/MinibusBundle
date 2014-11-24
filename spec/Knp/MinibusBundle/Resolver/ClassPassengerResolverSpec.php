<?php

namespace spec\Knp\MinibusBundle\Resolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\MinibusBundle\Resolver\ArgumentResolver;
use Symfony\Component\Finder\Finder;

class ClassPassengerResolverSpec extends ObjectBehavior
{
    function let(ArgumentResolver $resolver)
    {
        $this->beConstructedWith($resolver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Resolver\ClassPassengerResolver');
    }

    function it_is_a_resolver()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Resolver\Resolver');
    }

    function it_supports_array_with_class_key(Request $request)
    {
        $this->supports('foo', $request)->shouldReturn(false);
        $this->supports(['foo'], $request)->shouldReturn(false);
        $this->supports(['class' => 'something'], $request)->shouldReturn(true);
    }

    function it_throw_an_exception_when_trying_to_resolve_unexistent_class(
        Request $request
    ) {
        $this->shouldThrow('InvalidArgumentException')->duringResolve([
            'class' => 'Some\\Innexistent\\Class'
        ], $request);
    }

    function it_return_a_new_instance_of_the_specify_class(
        Request $request
    ) {
        $this->resolve(['class' => __CLASS__], $request)->shouldHaveType(__CLASS__);
    }

    function it_can_resolve_instance_arguments(
        Request $request,
        $resolver
    ) {
        $finder = new Finder;

        $resolver->resolve('@finder', $request)->willReturn($finder);

        $this->resolve([
            'class'     => 'Knp\MinibusBundle\Finder\ClassFinder',
            'arguments' => ['@finder']
        ], $request)->shouldHaveType('Knp\MinibusBundle\Finder\ClassFinder');
    }
}
