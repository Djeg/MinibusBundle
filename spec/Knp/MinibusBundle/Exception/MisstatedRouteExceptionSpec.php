<?php

namespace spec\Knp\MinibusBundle\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MisstatedRouteExceptionSpec extends ObjectBehavior
{
    function let(\Exception $reason)
    {
        $this->beConstructedWith('route_name', $reason);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Exception\MisstatedRouteException');
    }

    function it_is_an_exception()
    {
        $this->shouldHaveType('Exception');
    }

    function it_create_automatically_the_message()
    {
        $this->getMessage()->shouldReturn('The route "route_name" has been misstated :-(');
    }
}
