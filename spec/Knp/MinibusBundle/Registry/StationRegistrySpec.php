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

    function it_is_an_iterator_aggregate_that_iterate_on_the_registry(
        Station $station1,
        Station $station2
    ) {
        $this->shouldHaveType('IteratorAggregate');

        $this->collect($station1, 'first_station');
        $this->collect($station2, 'second_station');

        $this->getIterator()->shouldIterateOverStations([
            'first_station'  => $station1,
            'second_station' => $station2,
        ]);
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

    function getMatchers()
    {
        return [
            'iterateOverStations' => function ($iterator, $stations) {
                $expectedKeys = array_keys($stations);
                $keys         = array_keys($iterator->getArrayCopy());
                $values       = array_values($stations);

                for ($i = 0; $i < count($stations); $i++) {
                    if ($expectedKeys[$i] !== $keys[$i]) {
                        return false;
                    }

                    if ($values[$i] !== $iterator[$expectedKeys[$i]]) {
                        return false;
                    }
                }

                return true;
            }
        ];
    }
}
