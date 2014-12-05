<?php

namespace Knp\MinibusBundle\Minibus;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Minibus\Minibus;
use Knp\Minibus\Minibus\HttpMinibus;

/**
 * Create different all the kind of minibus.
 */
class MinibusFactory
{
    /**
     * @param Request  $request
     * @param Response $response
     * @param Minibus  $wrappedBus
     *
     * @return HttpMinibus
     */
    public function createHttpMinibus(
        Request $request,
        Response $response = null,
        Minibus $wrappedBus = null
    ) {
        return new HttpMinibus($request, $response, $wrappedBus);
    }
}
