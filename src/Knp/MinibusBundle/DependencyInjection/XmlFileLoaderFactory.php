<?php

namespace Knp\MinibusBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Create instance of an XmlFileLoader
 */
class XmlFileLoaderFactory
{
    public function create(ContainerBuilder $container, FileLocatorInterface $locator)
    {
        return new XmlFileLoader($container, $locator);
    }
}
