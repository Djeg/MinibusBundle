<?php

namespace spec\Knp\MinibusBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Minibus\Event\StartEvent;
use Knp\Minibus\Minibus;
use Symfony\Component\HttpFoundation\Request;
use Knp\MinibusBundle\Resolver\Resolver;

class PassengerResolverListenerSpec extends ObjectBehavior
{
    function let(RequestStack $stack)
    {
        $this->beConstructedWith($stack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\EventListener\PassengerResolverListener');
    }

    function it_resolve_passengers_stored_in_the_request_attributes(
        $stack,
        Request $request,
        ParameterBag $attributes,
        StartEvent $event,
        Minibus $minibus,
        Resolver $firstResolver,
        Resolver $secondResolver
    ) {
        $stack->getCurrentRequest()->willReturn($request);
        $request->attributes = $attributes;
        $event->getMinibus()->willReturn($minibus);

        $attributes->get('_passengers', [])->willReturn([
            'foo' => 'bar'
        ]);

        $this->addResolver($firstResolver);
        $this->addResolver($secondResolver);

        $firstResolver->supports('bar', $request)->willReturn(false);
        $secondResolver->supports('bar', $request)->willReturn(true);
        $firstResolver->resolve('bar', $request)->shouldNotBeCalled();
        $secondResolver->resolve('bar', $request)->willReturn('resolved');

        $minibus->addPassenger('foo', 'resolved')->shouldBeCalled();
        $attributes->set('foo', 'resolved')->shouldBeCalled();

        $this->resolveRequestPassengers($event);
    }
}
