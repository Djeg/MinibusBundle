<?php

namespace spec\Knp\MinibusBundle\Station;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\Minibus\Station;

class NamedStationContainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Station\NamedStationContainer');
    }

    function it_contains_named_station_and_can_retrieve_them(
        Station $stationOne,
        Station $stationTwo
    ) {
        $this->add('one', $stationOne);
        $this->add('two', $stationTwo);

        $this->get('one')->shouldReturn($stationOne);
        $this->get('two')->shouldReturn($stationTwo);

        $this
            ->shouldThrow('Knp\MinibusBundle\Exception\AlwaysRegisterStationException')
            ->duringAdd('one', $stationOne)
        ;

        $this
            ->shouldThrow('Knp\MinibusBundle\Exception\UnregisteredStationException')
            ->duringGet('unexistent')
        ;
    }
}
