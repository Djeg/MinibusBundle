<?php

namespace Knp\MinibusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Knp\Minibus\Config\TreeBuilderFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configure the dependency injection component.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        return new TreeBuilder;
    }
}
