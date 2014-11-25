<?php

namespace Knp\MinibusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Knp\Minibus\Config\TreeBuilderFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configure the dependency injection component.
 *
 * @author David Jegat <david.jegat@gmail.com>
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
