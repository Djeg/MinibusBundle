<?php

namespace Knp\MinibusBundle\Station;

use Knp\Minibus\Station;
use Knp\MinibusBundle\Exception\AlwaysRegisterStationException;
use Knp\MinibusBundle\Exception\UnregisteredStationException;

/**
 * Contains stations (... no way ...).
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class NamedStationContainer
{
    private $stations;

    public function __construct()
    {
        $this->stations = [];
    }

    /**
     * @param string $name
     * @param Station $station
     *
     * @throws AlwaysRegisterStationException
     *
     * @return NamedStationContainer
     */
    public function add($name, Station $station)
    {
        if (isset($this->stations[$name])) {
            throw new AlwaysRegisterStationException(sprintf(
                'A station named %s is always register in the container.',
                $name
            ));
        }

        $this->stations[$name] = $station;

        return $this;
    }

    /**
     * @param mixed $name
     *
     * @throws UnregisterStationException
     *
     * @return Station
     */
    public function get($name)
    {
        if (!isset($this->stations[$name])) {
            throw new UnregisteredStationException(sprintf(
                'The station %s is not registered in the container :-(.',
                $name
            ));
        }

        return $this->stations[$name];
    }
}
