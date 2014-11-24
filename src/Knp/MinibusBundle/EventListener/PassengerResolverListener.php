<?php

namespace Knp\MinibusBundle\EventListener;

use Knp\Minibus\Event\LineEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Minibus\Event\StartEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\MinibusBundle\Resolver\Resolver;

/**
 * Launched on the LineEvents::START, it resolved stored passengers in the
 * request attributes.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class PassengerResolverListener
{
    /**
     * @var RequestStack $requestStack
     */
    private $requestStack;

    /**
     * @var Resolver[] $resolvers
     */
    private $resolvers;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->resolvers    = [];
    }

    /**
     * Resolve the passengers store into the request attributes as '_passengers'.
     *
     * @param StartEvent $event
     */
    public function resolveRequestPassengers(StartEvent $event)
    {
        if (null === $request = $this->requestStack->getCurrentRequest()) {
            return;
        }

        $minibus    = $event->getMinibus();
        $passengers = $request->attributes->get('_passengers', []);

        foreach ($passengers as $name => $passenger) {
            foreach ($this->resolvers as $resolver) {
                if (!$resolver->supports($passenger, $request)) {
                    continue;
                }

                $passenger = $resolver->resolve($passenger, $request);
            }

            $request->attributes->set($name, $passenger);
            $minibus->addPassenger($name, $passenger);
        }
    }

    /**
     * @param Resolver $resolver
     *
     * @return PassengerResolverListener
     */
    public function addResolver(Resolver $resolver)
    {
        $this->resolvers[] = $resolver;

        return $this;
    }
}
