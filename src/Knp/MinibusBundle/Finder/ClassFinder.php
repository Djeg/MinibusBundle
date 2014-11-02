<?php

namespace Knp\MinibusBundle\Finder;

use Symfony\Component\Finder\Finder;

/**
 * Find easily all the classes inside a directory.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class ClassFinder
{
    /**
     * @var Finder $finder
     */
    private $finder;

    /**
     * @param Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param mixed $path
     * @param mixed $namespace
     *
     * @return \ReflectionClass[]
     */
    public function find($path, $namespace)
    {
        $reflections = [];
        $items       = $this->finder
            ->files()
            ->name('*.php')
            ->in($path)
        ;

        foreach ($items as $item) {
            $reflections[] = new \ReflectionClass(sprintf(
                '%s\%s',
                $namespace,
                $item->getBasename('.php')
            ));
        }

        return $reflections;
    }

    /**
     * @param string $interface
     * @param mixed  $path
     * @param mixed  $namespace
     *
     * @return \ReflectionClass[]
     */
    public function findImplementation($interface, $path, $namespace)
    {
        $reflections     = $this->find($path, $namespace);
        $implementations = [];

        foreach ($reflections as $reflection) {
            if (in_array($interface, $reflection->getInterfaceNames())) {
                $implementations[] = $reflection;
            }
        }

        return $implementations;
    }
}
