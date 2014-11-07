<?php

namespace Knp\MinibusBundle\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Knp\MinibusBundle\DependencyInjection\Compiler\PassFactory;

/**
 * This bundle is the entry point of a minibus application. You can use
 * minibus witheout inherit from this bundle, but for some "shortcuts" like
 * auto station registrations etc, you must inherit from this bundle.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class MinibusBundle extends Bundle
{
    /**
     * @var PassFactory $passFactory
     */
    private $passFactory;

    /**
     * @param PassFactory $passFactory
     */
    public function __construct(PassFactory $passFactory)
    {
        $this->passFactory = $passFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass($this->passFactory->createAutoStationRegistration($this));
    }
}
