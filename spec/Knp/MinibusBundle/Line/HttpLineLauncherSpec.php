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
use Knp\MinibusBundle\Registry\TerminusRegistry;
use Knp\Minibus\Terminus\Terminus;

class HttpLineLauncherSpec extends ObjectBehavior
{
    function let(StationRegistry $stationRegistry, TerminusRegistry $terminusRegistry, Line $line, MinibusFactory $factory)
    {
        $this->beConstructedWith($stationRegistry, $terminusRegistry, $line, $factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Line\HttpLineLauncher');
    }

    function it_launch_a_minibus_line(
        Request $request,
        ParameterBag $attributes,
        $stationRegistry,
        $terminusRegistry,
        $line,
        $factory,
        Station $station,
        HttpMinibus $minibus,
        Response $response,
        Terminus $terminus
    ) {
        $request->attributes = $attributes;
        $attributes->get('_line')->willReturn([
            'some_station' => ['some configuration']
        ]);
        $attributes->get('_terminus')->willReturn([
            'a terminus' => ['some configuration']
        ]);

        $stationRegistry->retrieve('some_station')->willReturn($station);
        $terminusRegistry->retrieve('a terminus')->willReturn($terminus);

        $factory->createHttpMinibus($request)->willReturn($minibus);

        $line->addStation($station, ['some configuration'])->shouldBeCalled();
        $line->setTerminus($terminus, ['some configuration'])->shouldBeCalled();

        $line->lead($minibus)->willReturn('The result');
        $minibus->getResponse()->willReturn($response);

        $response->setContent('The result')->shouldBeCalled();

        $this->launch($request)->shouldReturn($response);
    }
}
