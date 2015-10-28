<?php

namespace Knp\MinibusBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Knp\MinibusBundle\DependencyInjection\Compiler\CompilerPassFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

/**
 * The famous minibus in one bundle. No way ... o_O
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

        $container->addCompilerPass($this->compilerPassFactory->createStationRegistration(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass($this->compilerPassFactory->createTerminusRegistration(), PassConfig::TYPE_OPTIMIZE);
    }
}
