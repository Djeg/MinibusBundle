<?php

namespace spec\Knp\MinibusBundle\Minibus;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Minibus\Minibus;
use Knp\Minibus\Http\HttpMinibus;

class MinibusFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Minibus\MinibusFactory');
    }

    function it_create_htp_minibus(Request $request)
    {
        $this->createHttpMinibus($request)->shouldReturnHttpMinibusWith($request);
    }

    function getMatchers()
    {
        return [
            'returnHttpMinibusWith' => function ($minibus, $request) {
                return $minibus instanceof HttpMinibus &&
                    $minibus->getRequest() === $request
                ;
            }
        ];
    }
}
