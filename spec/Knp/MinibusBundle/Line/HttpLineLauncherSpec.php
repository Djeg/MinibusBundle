<?php

namespace spec\Knp\MinibusBundle\Line;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\MinibusBundle\Registry\StationRegistry;
use Knp\Minibus\Line;
use Knp\MinibusBundle\Minibus\MinibusFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Minibus\Station;
use Knp\Minibus\Http\HttpMinibus;
use Symfony\Component\HttpFoundation\Response;

class HttpLineLauncherSpec extends ObjectBehavior
{
    function let(StationRegistry $registry, Line $line, MinibusFactory $factory)
    {
        $this->beConstructedWith($registry, $line, $factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Line\HttpLineLauncher');
    }

    function it_launch_a_minibus_line(
        Request $request,
        ParameterBag $attributes,
        $registry,
        $line,
        $factory,
        Station $station,
        HttpMinibus $minibus,
        Response $response
    ) {
        $request->attributes = $attributes;
        $attributes->get('_line')->willReturn([
            'some_station' => ['some configuration']
        ]);
        $registry->retrieve('some_station')->willReturn($station);
        $factory->createHttpMinibus($request)->willReturn($minibus);

        $line->addStation($station)->shouldBeCalled();

        $line->lead($minibus)->willReturn('The result');
        $minibus->getResponse()->willReturn($response);

        $response->setContent('The result')->shouldBeCalled();

        $this->launch($request)->shouldReturn($response);
    }
}
