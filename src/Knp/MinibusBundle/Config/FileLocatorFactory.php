<?php

namespace Knp\MinibusBundle\Config;

use Symfony\Component\Config\FileLocator;

/**
 * Create instance of FileLocator.
 *
 * @author David Jegat <david.jegat@gmail.com>
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
