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

class PassengerResolverListenerSpec extends ObjectBehavior
{
    function let(RequestStack $stack, ContainerInterface $container)
    {
        $this->beConstructedWith($stack, $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\EventListener\PassengerResolverListener');
    }

    function it_resolve_passengers_stored_in_the_request_attributes(
        $stack,
        $container,
        Request $request,
        ParameterBag $attributes,
        StartEvent $event,
        Minibus $minibus
    ) {
        $event->getMinibus()->willReturn($minibus);
        $stack->getCurrentRequest()->willReturn($request);
        $request->attributes = $attributes;

        $attributes->get('_passengers', [])->willReturn([
            'foo' => 'bar'
        ]);

        $minibus->addPassenger('foo', 'bar')->shouldBeCalled();
        $attributes->set('foo', 'bar')->shouldBeCalled();

        $this->resolveRequestPassengers($event);
    }
}
