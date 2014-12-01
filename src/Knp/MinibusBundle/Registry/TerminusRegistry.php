<?php

namespace Knp\MinibusBundle\Registry;

use Knp\MinibusBundle\Exception\AlreadyRegisteredTerminusException;
use Knp\Minibus\Terminus;
use Knp\MinibusBundle\Exception\UnregisteredTerminusException;

/**
 * Collect terminus and retrieve it.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class TerminusRegistry implements \IteratorAggregate
{
    /**
     * @var Terminus[] $registry
     */
    private $registry;

    public function __construct()
    {
        $this->registry = [];
    }

    /**
     * @param Terminus $terminus
     * @param mixed $name
     *
     * @throws AlreadyRegisteredTerminusException
     *
     * @return TerminusRegistry
     */
    public function collect(Terminus $terminus, $name)
    {
        if (isset($this->registry[$name])) {
            throw new AlreadyRegisteredTerminusException(sprintf(
                'The terminus named "%s" has balready been registered.',
                $name
            ));
        }

        $this->registry[$name] = $terminus;

        return $this;
    }

    /**
     * @param string $terminusName
     *
     * @throws UnregisteredTerminusException
     *
     * @return Terminus
     */
    public function retrieve($terminusName)
    {
        if (!isset($this->registry[$terminusName])) {
            throw new UnregisteredTerminusException(sprintf(
                'The terminus "%s" has not been registered yet :-(.',
                $terminusName
            ));
        }

        return $this->registry[$terminusName];
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->registry);
    }
}
