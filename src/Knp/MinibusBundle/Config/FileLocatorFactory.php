<?php

namespace Knp\MinibusBundle\Config;

use Symfony\Component\Config\FileLocator;

/**
 * Create instance of FileLocator.
 */
class FileLocatorFactory
{
    /**
     * @param string $path
     *
     * @return FileLocator
     */
    public function create($path)
    {
        return new FileLocator($path);
    }
}
