<?php

namespace Knp\MinibusBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Knp\MinibusBundle\DependencyInjection\Compiler\CompilerPassFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * The famous minibus in one bundle o_O.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class KnpMinibusBundle extends Bundle
{
    /**
     * @var CompilerPassFactory $compilerPassFactory
     */
    private $compilerPassFactory;

    /**
     * @param CompilerPassFactory $compilerPassFactory
     */
    public function __construct(CompilerPassFactory $compilerPassFactory = null)
    {
        $this->compilerPassFactory = $compilerPassFactory ?: new CompilerPassFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass($this->compilerPassFactory->createStationRegistration());
    }
}
