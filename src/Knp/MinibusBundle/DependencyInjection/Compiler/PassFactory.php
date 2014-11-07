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
class PassFactory
{
    /**
     * @param Bundle            $bundle
     * @param ClassFinder       $finder
     * @param DefinitionFactory $definitionFactory
     *
     * @return AutoRegisterStationPass
     */
    public function createAutoStationRegistration(
        Bundle            $bundle,
        ClassFinder       $finder = null,
        DefinitionFactory $definitionFactory = null
    ) {
        return new AutoRegisterStationPass($bundle, $finder, $definitionFactory);
    }
}
