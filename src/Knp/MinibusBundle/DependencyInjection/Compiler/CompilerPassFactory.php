<?php

namespace Knp\MinibusBundle\DependencyInjection\Compiler;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Knp\MinibusBundle\Finder\ClassFinder;
use Knp\MinibusBundle\DependencyInjection\DefinitionFactory;

/**
 * For SOLID principle convenient, and to disable any contributor to create
 * compiler pass by hand, this factory can create any MinibusBundle compiler
 * pass.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class CompilerPassFactory
{
    /**
     * @return AutoRegisterStationPass
     */
    public function createStationRegistration()
    {
        return new RegisterStationPass;
    }
}
