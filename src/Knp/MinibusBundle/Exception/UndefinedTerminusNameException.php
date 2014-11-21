<?php

namespace Knp\MinibusBundle\Exception;

/**
 * Throw during the compilation pass if you forget to add a name attribute
 * on the tagged terminus.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class UndefinedTerminusNameException extends \Exception
{
}
