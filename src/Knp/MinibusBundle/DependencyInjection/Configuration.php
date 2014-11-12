<?php

namespace Knp\MinibusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Knp\Minibus\Config\TreeBuilderFactory;

/**
 * Configure the dependency injection component.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var TreeBuilderFactory $builderFactory
     */
    private $builderFactory;

    /**
     * @param TreeBuilderFactory $builderFactory
     */
    public function __construct(TreeBuilderFactory $builderFactory = null)
    {
        $this->builderFactory = $builderFactory ?: new TreeBuilderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = $this->builderFactory->create();
        $root = $treeBuilder->root('knp_minibus');

        return $treeBuilder;
    }
}
