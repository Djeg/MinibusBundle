<?php

namespace Knp\MinibusBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Knp\MinibusBundle\Finder\ClassFinder;
use Knp\MinibusBundle\DependencyInjection\DefinitionFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Common\Inflector\Inflector;

/**
 * Auto register all the station in a bundle namespace. By default it will look
 * on the bundleNamespace\Station recursively and auto register the stations.
 *
 * @author David Jegat <david.jegat@gmail.com>
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
     * @param Bundle $bundle
     * @param ClassFinder $finder
     * @param DefinitionFactory $definitionFactory
     */
    public function __construct(
        BundleInterface $bundle,
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

        $bundleAlias = $this->deduceBundleAlias($this->bundle);

        foreach ($reflections as $reflection) {
            $alias       = $this->deduceStationAlias($reflection);
            $serviceName = $this->deduceServiceName($bundleAlias, $this->bundle, $reflection);

            if ($container->hasDefinition($serviceName)) {
                continue;
            }

            $definition  = $this->definitionFactory->create($reflection->getName());

            $definition->addTag('knp_minibus.station', [
                'alias' => sprintf('%s%s', $bundleAlias, $alias)
            ]);

            $container->setDefinition($serviceName, $definition);
        }
    }

    /**
     * @param \ReflectionClass $reflection
     *
     * @return string
     */
    private function deduceStationAlias(\ReflectionClass $reflection)
    {
        $baseNamespace  = $this->bundle->getNamespace() . '\\Station';
        $addedNamespace = str_replace($baseNamespace, '', $reflection->getName());

        $explodedNamespace = explode('\\', $addedNamespace);

        $formatedAlias = [];

        foreach ($explodedNamespace as $part) {
            $formatedAlias[] = Inflector::tableize(str_replace('Station', '', $part));
        }

        return implode('.', $formatedAlias);
    }

    /**
     * @param Bundle $bundle
     *
     * @return string
     */
    private function deduceBundleAlias(BundleInterface $bundle)
    {
        if (null !== $extension = $bundle->getContainerExtension()) {
            return $extension->getAlias();
        }

        return Inflector::tableize(str_replace('Bundle', '', get_class($bundle)));
    }

    /**
     * @param string           $bundleAlias
     * @param Bundle           $bundle
     * @param \ReflectionClass $reflection
     *
     * @return string
     */
    private function deduceServiceName($bundleAlias, BundleInterface  $bundle, \ReflectionClass $reflection)
    {
        $explodedName = explode('\\', $reflection->getName());
        $members      = [];
        $find         = false;

        foreach ($explodedName as $name) {
            if ($name === 'Station') {
                $find = true;
            }

            if (!$find) {
                continue;
            }

            $members[] = $name;
        }

        $members[count($members) - 1] = str_replace(
            'Station',
            '',
            $members[count($members) - 1]
        );

        return $bundleAlias . '.' . implode('.', array_map(function ($member) {
            return Inflector::tableize($member);
        }, $members));
    }
}
