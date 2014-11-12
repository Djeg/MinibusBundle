<?php

namespace Knp\MinibusBundle\Reflection;

/**
 * Create easily reflection class based on an object namespace.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class ReflectionClassFactory
{
    /**
     * @param string $namespace
     *
     * @return \ReflectionClass
     */
    public function create($namespace)
    {
        return new \ReflectionClass($namespace);
    }
}
