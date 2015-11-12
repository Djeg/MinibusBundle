<?php

namespace Knp\MinibusBundle\Station\ContainerAwareStation;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Help your station to deal with the container injection.
 */
trait Container
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *
     * @return mixed, the current instance
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
