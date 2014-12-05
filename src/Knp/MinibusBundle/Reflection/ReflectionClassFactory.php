<?php

namespace Knp\MinibusBundle\Reflection;

/**
 * Create easily reflection class based on an object namespace.
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
