<?php

namespace Knp\MinibusBundle\EventListener;

use Knp\Minibus\Event\LineEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Minibus\Event\StartEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @param RequestStack       $requestStack
     * @param ContainerInterface $container
     */
    public function __construct(RequestStack $requestStack, ContainerInterface $container)
    {
        $this->requestStack = $requestStack;
        $this->container    = $container;
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
            $passenger = $this->resolvePassenger($passenger, $request);

            $request->attributes->set($name, $passenger);
            $minibus->addPassenger($name, $passenger);
        }
    }

    /**
     * Resolve a given passenger if needed
     *
     * @param mixed   $passenger
     * @param Request $request
     *
     * @return mixed the resolved passenger.
     */
    private function resolvePassenger($passenger, Request $request)
    {
        if (!is_array($passenger)) {
            return $passenger;
        }

        if (isset($passenger['service'])) {
            return $this->resolveService($passenger, $request);
        }

        if (isset($passenger['class'])) {
            return $this->resolveClass($passenger, $request);
        }

        return $passenger;
    }

    /**
     * Resolve a passenger defined as a service.
     *
     * @param mixed   $passenger
     * @param Request $request
     *
     * @return mixed the service calling result.
     */
    public function resolveService($passenger, Request $request)
    {
        $service = $this->container->get($passenger['service']);
        $method  = isset($passenger['method']) ? $passenger['method'] : null;

        if (null === $method) {
            return $this->container->get($service);
        }

        $arguments = isset($passenger['arguments']) ? $passenger['arguments'] : [];

        foreach ($arguments as &$argument) {
            $argument = $this->resolveArgument($argument, $request);
        }

        return call_user_func_array([$service, $method], $arguments);
    }

    /**
     * Resolve a given class.
     *
     * @param mixed   $passenger
     * @param Request $request
     *
     * @throws InvalidArgumentException if the class does not exists.
     *
     * @return mixed the class instance, or some method result.
     */
    private function resolveClass($passenger, Request $request)
    {
        $class     = $passenger['class'];
        $method    = isset($passenger['method']) ? $passenger['method'] : null;
        $arguments = isset($passenger['arguments']) ? $passenger['arguments'] : [];

        foreach ($arguments as &$argument) {
            $argument = $this->resolveArgument($argument, $request);
        }

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf(
                'The class "%s" does not exists. Maybe a misspelling in your routing file :-(.',
                $class
            ));
        }

        if (null === $method) {
            return (new \ReflectionClass($class))->newInstanceArgs($arguments);
        }

        if (!method_exists($class, $method)) {
            throw new \InvalidArgumentException(sprintf(
                'The method %s::%s is not callable. Maybe a type in your routing file(s) :-/.',
                $class,
                $method
            ));
        }

        $method = new \ReflectionMethod($class, $method);

        if (!$method->isStatic() or !$method->isPublic()) {
            throw new \InvalidArgumentException(sprintf(
                'The method %s::%s is not a static/public method. Maybe a typo in your routing file(s) ?',
                $class,
                $method
            ));
        }

        return call_user_func_array([$class, $method], $arguments);
    }

    /**
     * Resolve an argument. Note: it supports parameters and services syntax.
     *
     * @param mixed   $argument
     * @param Request $request
     *
     * @return mixed the argument value.
     */
    public function resolveArgument($argument, Request $request)
    {
        if (!is_string($argument)) {
            return $argument;
        }

        if (strpos($argument, '$') === 0) {
            return $request->attributes->get(substr($argument, 1));
        }

        if (strpos($argument, '%') === 0) {
            return $this->container->getParameter(substr($argument, 1, -1));
        }

        if (strpos($argument, '@') === 0) {
            return $this->container->getService(substr($argument, 1));
        }

        return $argument;
    }
}
