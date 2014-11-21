<?php

namespace Knp\MinibusBundle\Exception;

/**
 * Throw when you try to retrieve a non registered terminus in 
 * the TerminusRegistry.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class UnregisteredTerminusException extends \Exception
{
}
