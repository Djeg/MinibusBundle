<?php

namespace Knp\MinibusBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Knp\MinibusBundle\Finder\ClassFinder;
use Knp\MinibusBundle\DependencyInjection\DefinitionFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Knp\MinibusBundle\Utils\NamingStrategist;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Auto register all the station in a bundle namespace. By default it will look
 * on the bundleNamespace\Station recursively and auto register the stations.
 */
class AutoRegisterStationPass implements CompilerPassInterface
{
    /**
     * @var Bundle $bundle
     */
    private $bundle;

    /**
     * @var ClassFinder $finder
     */
    private $finder;

    /**
     * @var DefinitionFactory $definitionFactory
     */
    private $definitionFactory;

    /**
     * @param Bundle            $bundle
     * @param ClassFinder       $finder
     * @param DefinitionFactory $definitionFactory
     */
    public function __construct(
        Bundle $bundle,
        ClassFinder $finder = null,
        DefinitionFactory $definitionFactory = null
    ) {
        $this->bundle            = $bundle;
        $this->finder            = $finder ?: new ClassFinder;
        $this->definitionFactory = $definitionFactory ?: new DefinitionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('knp_minibus.disable_station_auto_registration')) {
            return;
        }

        $reflections = $this->finder->findImplementation(
            'Knp\Minibus\Station',
            sprintf('%s/Station', $this->bundle->getPath()),
            sprintf('%s\\Station', $this->bundle->getNamespace())
        );

        foreach ($reflections as $reflection) {
            $serviceId = NamingStrategist::servicify($reflection->getName(), $this->bundle, 'Station');
            $alias     = NamingStrategist::stationify($reflection->getName(), $this->bundle);

            if ($container->hasDefinition($serviceId)) {
                continue;
            }

            $definition  = $this->definitionFactory->create($reflection->getName());

            $definition->addTag('knp_minibus.station', [
                'alias' => $alias
            ]);

            if ($reflection->implementsInterface('Knp\MinibusBundle\Station\ContainerAwareStation')) {
                $containerReference = new Reference('container');

                $definition->addMethodCall('setContainer', [$containerReference]);
            }

            $container->setDefinition($serviceId, $definition);
        }
    }
}
