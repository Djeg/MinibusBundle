<?php

namespace Knp\MinibusBundle\Exception;

/**
 * Throw when you try to register an already registered terminus in the
 * TerminusCenter.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class AlreadyRegisteredTerminusException extends \Exception
{
}
