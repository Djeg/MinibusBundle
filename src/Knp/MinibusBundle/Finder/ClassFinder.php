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
    public function __construct(Finder $finder = null)
    {
        $this->finder = $finder ?: new Finder;
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
                $this->deduceBasename($item, $namespace)
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

    private function deduceBasename(\SplFileInfo $item, $namespace)
    {
        $explodedNamespaces = explode('\\', $namespace);
        $lastNamespace      = array_pop($explodedNamespaces);
        $explodedPath       = explode(DIRECTORY_SEPARATOR, $item->getPath());
        $found              = false;
        $basepath           = [];

        foreach ($explodedPath as $path) {
            if ($found) {
                $basepath[] = $path;
            }

            if ($path === $lastNamespace) {
                $found = true;
            }
        }

        if (empty($basepath)) {
            return $item->getBasename('.php');
        }

        return sprintf(
            '%s\\%s',
            implode(DIRECTORY_SEPARATOR, $basepath),
            $item->getBasename('.php')
        );
    }
}
