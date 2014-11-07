<?php

namespace Knp\MinibusBundle\DependencyInjection\Compiler;

use Knp\MinibusBundle\DependencyInjection\ReferenceFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Knp\MinibusBundle\Exception\UndefinedStationAliasException;

/**
 * Register tagged station into the named station container.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class StationPass implements CompilerPassInterface
{
    /**
     * @var ReferenceFactory $referenceFactory
     */
    private $referenceFactory;

    /**
     * @param ReferenceFactory $referenceFactory
     */
    public function __construct(ReferenceFactory $referenceFactory)
    {
        $this->referenceFactory = $referenceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('knp_minibus.station.named_station_container')) {
            return;
        }

        $definition       = $container->getDefinition('knp_minibus.station.named_station_container');
        $taggedServiceIds = $container->findTaggedServiceIds('knp_minibus.station');

        foreach ($taggedServiceIds as $id => $attributes) {
            if (!isset($attributes['alias'])) {
                throw new UndefinedStationAliasException(sprintf(
                    'The station defined in the service "%s" has no alias. You must precise a station alias.',
                    $id
                ));
            }

            $definition->addMethodCall(
                'add',
                [$attributes['alias'], $this->referenceFactory->create($id)]
            );
        }
    }
}
