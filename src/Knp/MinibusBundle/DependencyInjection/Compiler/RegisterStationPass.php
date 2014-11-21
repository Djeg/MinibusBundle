<?php

namespace Knp\MinibusBundle\DependencyInjection\Compiler;

use Knp\MinibusBundle\DependencyInjection\ReferenceFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Knp\MinibusBundle\Exception\UndefinedStationNameException;

/**
 * Register tagged station into the station registry.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class RegisterStationPass implements CompilerPassInterface
{
    /**
     * @var ReferenceFactory $referenceFactory
     */
    private $referenceFactory;

    /**
     * @param ReferenceFactory $referenceFactory
     */
    public function __construct(ReferenceFactory $referenceFactory = null)
    {
        $this->referenceFactory = $referenceFactory ?: new ReferenceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('knp_minibus.station_registry')) {
            return;
        }

        $definition       = $container->getDefinition('knp_minibus.station_registry');
        $taggedServiceIds = $container->findTaggedServiceIds('knp_minibus.station');

        foreach ($taggedServiceIds as $id => $tagAttributes) {
            $reference = $this->referenceFactory->create($id);
            $name      = null;
            foreach ($tagAttributes  as $attributes) {
                if (isset($attributes['alias'])) {
                    $name = $attributes['alias'];
                    break;
                }
            }

            if (null === $name) {
                throw new UndefinedStationNameException(sprintf(
                    'The service "%s" is tagged as a minibus station but does not have an alias :-(.',
                    $id
                ));
            }

            $definition->addMethodCall('collect', [$reference, $name]);
        }
    }
}
