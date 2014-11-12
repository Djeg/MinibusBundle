<?php

namespace spec\Knp\MinibusBundle\Registry;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\Minibus\Station;

class StationRegistrySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Registry\StationRegistry');
    }

    function it_collect_named_station(Station $station1, Station $station2)
    {
        $this->collect($station1, 'station1');
        $this->collect($station2, 'station2');

        $this->retrieve('station1')->shouldReturn($station1);
        $this->retrieve('station2')->shouldReturn($station2);
    }

    function it_can_not_register_same_named_station_twice(Station $station1, Station $station2)
    {
        $this->collect($station1, 'station1');
        $this->shouldThrow('Knp\MinibusBundle\Exception\AlreadyRegisteredStationException')->duringCollect($station2, 'station1');
    }

    function it_can_not_retrieve_non_registered_station_name()
    {
        $this->shouldThrow('Knp\MinibusBundle\Exception\UnregisteredStationException')->duringRetrieve('unexistent');
    }
}
