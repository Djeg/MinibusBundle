<?php

namespace spec\Knp\MinibusBundle\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UnregisteredTerminusExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Exception\UnregisteredTerminusException');
    }

    function it_is_a_exception()
    {
        $this->shouldHaveType('Exception');
    }
}
