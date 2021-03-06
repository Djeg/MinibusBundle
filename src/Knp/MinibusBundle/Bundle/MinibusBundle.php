<?php

namespace Knp\MinibusBundle\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Knp\MinibusBundle\DependencyInjection\Compiler\CompilerPassFactory;

/**
 * This bundle is the entry point of a minibus application. You can use
 * minibus witheout inherit from this bundle, but for some "shortcuts" like
 * auto station registrations etc, you must inherit from this bundle.
 */
class MinibusBundle extends Bundle
{
    /**
     * @var CompilerPassFactory $passFactory
     */
    private $passFactory;

    /**
     * @param CompilerPassFactory $passFactory
     */
    public function __construct(CompilerPassFactory $passFactory = null)
    {
        $this->passFactory = $passFactory ?: new CompilerPassFactory;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass($this->passFactory->createAutoStationRegistration($this));
        $container->addCompilerPass($this->passFactory->createAutoTerminusRegistration($this));
    }
}
