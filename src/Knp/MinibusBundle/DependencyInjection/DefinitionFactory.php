<?php

namespace Knp\MinibusBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;

/**
 * Create a dependency injection definition.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class DefinitionFactory
{
    /**
     * @param mixed $class
     * @param array $arguments
     *
     * @return Definition
     */
    public function create($class, array $arguments = [])
    {
        return new Definition($class, $arguments);
    }
}
