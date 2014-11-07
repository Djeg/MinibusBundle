<?php

namespace Knp\MinibusBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Reference;

/**
 * Simply create a reference :)
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class ReferenceFactory
{
    /**
     * @param string $id
     *
     * @return Reference
     */
    public function create($id)
    {
        return new Reference($id);
    }
}
