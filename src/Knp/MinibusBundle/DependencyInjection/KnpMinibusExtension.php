<?php

namespace Knp\MinibusBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;
use Knp\MinibusBundle\Config\FileLocatorFactory;

/**
 * Knp Minibus dependency injection extension.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class KnpMinibusExtension extends Extension
{
    /**
     * @var Configuration $configuration
     */
    private $configuration;

    /**
     * @var Processor $processor
     */
    private $processor;

    /**
     * @var XmlFileLoaderFactory $loaderFactory
     */
    private $loaderFactory;

    /**
     * @var FileLocatorFactory $locatorFactory
     */
    private $locatorFactory;

    /**
     * @param Configuration        $configuration
     * @param Processor            $processor
     * @param XmlFileLoaderFactory $loaderFactory
     * @param FileLocatorFactory   $locatorFactory
     */
    public function __construct(
        Configuration        $configuration = null,
        Processor            $processor = null,
        XmlFileLoaderFactory $loaderFactory = null,
        FileLocatorFactory   $locatorFactory = null
    ) {
        $this->configuration  = $configuration ?: new Configuration;
        $this->processor      = $processor ?: new Processor;
        $this->loaderFactory  = $loaderFactory ?: new XmlFileLoaderFactory;
        $this->locatorFactory = $locatorFactory ?: new FileLocatorFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processor->processConfiguration($this->configuration, $configs);

        $locator = $this->locatorFactory->create(sprintf('%s/../Resources/config', __DIR__));
        $loader = $this->loaderFactory->create($container, $locator);

        $loader->load('services.xml');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'knp_minibus';
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return $this->configuration;
    }
}
