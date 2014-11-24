<?php

namespace Knp\MinibusBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;

/**
 * A resolver can resolve given subject into something else.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
interface Resolver
{
    /**
     * @param mixed   $subject
     * @param Request $request
     *
     * @return mixed the resolved subject
     */
    public function resolve($subject, Request $request);

    /**
     * @param mixed   $subject
     * @param Request $request
     *
     * @return boolean
     */
    public function supports($subject, Request $request);
}
