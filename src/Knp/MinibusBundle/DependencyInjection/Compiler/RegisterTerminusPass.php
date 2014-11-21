<?php

namespace Knp\MinibusBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Knp\MinibusBundle\DependencyInjection\ReferenceFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Knp\MinibusBundle\Exception\UndefinedTerminusNameException;

/**
 * Register tagged terminus into the terminus registry.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class RegisterTerminusPass implements CompilerPassInterface
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
        if (!$container->hasDefinition('knp_minibus.terminus_registry')) {
            return;
        }

        $definition       = $container->getDefinition('knp_minibus.terminus_registry');
        $taggedServiceIds = $container->findTaggedServiceIds('knp_minibus.terminus');

        foreach ($taggedServiceIds as $id => $tagAttributes) {
            $name      = null;
            foreach ($tagAttributes as $attributes) {
                if (isset($attributes['name'])) {
                    $name = $attributes['name'];
                    break;
                }
            }

            $reference = $this->referenceFactory->create($id);

            if (null === $name) {
                throw new UndefinedTerminusNameException(sprintf(
                    'The service "%s" is tagged as a minibus terminus but does not have a name :-(.',
                    $id
                ));
            }

            $definition->addMethodCall('collect', [$reference, $name]);
        }
    }
}
