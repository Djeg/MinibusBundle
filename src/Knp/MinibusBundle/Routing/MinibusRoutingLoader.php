<?php

namespace Knp\MinibusBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Knp\MinibusBundle\Routing\Factory\RoutingFactory;
use Knp\MinibusBundle\Routing\Reader\YamlResourceReader;
use Knp\MinibusBundle\Routing\Mapper\RouteMapper;

/**
 * Load a Minibus routing file. You can load an xml, yaml or php file in order
 * to defined your routing line informations.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class MinibusRoutingLoader implements LoaderInterface
{
    const TYPE = 'minibus';

    /**
     * @var RoutingFactory $routingFactory
     */
    private $routingFactory;

    /**
     * @var YamlResourceReader $resourceReader
     */
    private $resourceReader;

    /**
     * @var RouteMapper[] $mappers
     */
    private $mappers;

    /**
     * @param YamlResourceReader $resourceReader
     * @param RoutingFactory $routingFactory
     */
    public function __construct(
        YamlResourceReader $resourceReader,
        RoutingFactory $routingFactory = null
    ) {
        $this->resourceReader = $resourceReader;
        $this->routingFactory = $routingFactory ?: new RoutingFactory;
        $this->mappers        = [];
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $collection = $this->routingFactory->createCollection();
        $routing    = $this->resourceReader->read($resource);

        foreach ($routing as $name => $attributes) {
            $route = $this->routingFactory->createRoute($attributes['pattern']);

            foreach ($this->mappers as $mapper) {
                $mapper->map($name, $attributes, $route);
            }

            $collection->add($name, $route);
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        if (!is_string($type) or !is_string($resource)) {
            return false;
        }

        if ('.yml' !== substr($resource, -4) &&'.yaml' !== substr($resource, -5)) {
            return false;
        }

        if ($type !== self::TYPE) {
            return false;
        }

        return true;
    }

    /**
     * @param RouteMapper $mapper
     *
     * @return MinibusRoutingLoader
     */
    public function addMapper(RouteMapper $mapper)
    {
        $this->mappers[] = $mapper;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }
}
