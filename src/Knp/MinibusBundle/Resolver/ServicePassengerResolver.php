<?php

namespace Knp\MinibusBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Resolve a given passenger as array into a service.
 */
class ServicePassengerResolver implements Resolver
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var ArgumentResolver $resolver
     */
    private $resolver;

    /**
     * @param ContainerInterface $container
     * @param ArgumentResolver   $resolver
     */
    public function __construct(
        ContainerInterface $container,
        ArgumentResolver $resolver = null
    ) {
        $this->container = $container;
        $this->resolver  = $resolver ?: new ArgumentResolver($container);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($subject, Request $request)
    {
        $service = $this->container->get($subject['service']);
        $method  = isset($subject['method']) ? $subject['method'] : null;

        if (null === $method) {
            return $service;
        }

        $arguments = isset($subject['arguments']) ? $subject['arguments'] : [];

        foreach ($arguments as &$argument) {
            $argument = $this->resolver->resolve($argument, $request);
        }

        return call_user_func_array([$service, $method], $arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($subject, Request $request)
    {
        return is_array($subject) && isset($subject['service']);
    }
}
