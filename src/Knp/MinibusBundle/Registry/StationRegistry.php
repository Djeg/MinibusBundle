<?php

namespace Knp\MinibusBundle\Registry;

use Knp\Minibus\Station;
use Knp\MinibusBundle\Exception\UnregisteredStationException;
use Knp\MinibusBundle\Exception\AlreadyRegisteredStationException;

/**
 * A simple registry for all the defined stations.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class StationRegistry
{
    /**
     * @var Station[] $registry
     */
    private $registry;

    public function __construct()
    {
        $this->registry = [];
    }

    /**
     * Collect a station. Be carefull you can't collect same named station
     * twice.
     *
     * @param Station $station
     * @param string  $name
     *
     * @throws AlreadyRegisteredStationException
     *
     * @return StationRegistry
     */
    public function collect(Station $station, $name)
    {
        if (isset($this->registry[$name])) {
            throw new AlreadyRegisteredStationException(sprintf(
                'The station named "%s" has already been registered.',
                $name
            ));
        }

        $this->registry[$name] = $station;

        return $this;
    }

    /**
     * Retrieve a registered station by it's name. This method fail when you
     * retrieve non registered station name.
     *
     * @param string $name
     *
     * @throws UnregisteredStationException
     *
     * @return Station
     */
    public function retrieve($name)
    {
        if (!isset($this->registry[$name])) {
            throw new UnregisteredStationException(sprintf(
                'The station "%s" has not been registered yet :-(.',
                $name
            ));
        }

        return $this->registry[$name];
    }
}
