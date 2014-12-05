<?php

namespace Knp\MinibusBundle\Exception;

/**
 * Throw during the compilation pass if you forget to add a name attribute
 * on the tagged terminus.
 */
class UndefinedTerminusNameException extends \Exception
{
}
