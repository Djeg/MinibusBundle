<?php

namespace Knp\MinibusBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Resolve an argument for a given passenger definition.
 */
class ArgumentResolver implements Resolver
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($subject, Request $request)
    {
        if (!is_string($subject)) {
            return $subject;
        }

        if (strpos($subject, '$') === 0) {
            return $request->attributes->get(substr($subject, 1), null);
        }

        if (strpos($subject, '%') === 0) {
            return $this->container->getParameter(substr($subject, 1, -1));
        }

        if (strpos($subject, '@') === 0) {
            return $this->container->get(substr($subject, 1));
        }

        return $subject;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($subject, Request $request)
    {
        return true;
    }
}
