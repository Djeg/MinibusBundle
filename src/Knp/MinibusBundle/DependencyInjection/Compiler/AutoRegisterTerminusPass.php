<?php

namespace Knp\MinibusBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Knp\MinibusBundle\DependencyInjection\DefinitionFactory;
use Knp\MinibusBundle\Finder\ClassFinder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Knp\MinibusBundle\Utils\NamingStrategist;

/**
 * Auto register station of a bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class AutoRegisterTerminusPass implements CompilerPassInterface
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
        Bundle            $bundle,
        ClassFinder       $finder = null,
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
        if ($container->hasParameter('knp_minibus.disable_terminus_auto_registration')) {
            return;
        }

        $reflections = $this->finder->findImplementation(
            'Knp\Minibus\Terminus',
            sprintf('%s/Terminus', $this->bundle->getPath()),
            sprintf('%s\\Terminus', $this->bundle->getNamespace())
        );

        foreach ($reflections as $reflection) {
            $serviceId = NamingStrategist::servicify($reflection->getName(), $this->bundle, 'Terminus');
            $alias     = NamingStrategist::terminusify($reflection->getName(), $this->bundle);

            if ($container->hasDefinition($serviceId)) {
                continue;
            }

            $definition = $this->definitionFactory->create($reflection->getName());

            $definition->addTag('knp_minibus.terminus', [
                'alias' => $alias,
            ]);

            $container->setDefinition($serviceId, $definition);
        }
    }
}
