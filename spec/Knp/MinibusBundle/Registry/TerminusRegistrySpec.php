<?php

namespace spec\Knp\MinibusBundle\Registry;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\Minibus\Terminus\Terminus;

class TerminusRegistrySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Registry\TerminusRegistry');
    }

    function it_collect_named_terminus(Terminus $terminus)
    {
        $this->collect($terminus, 'some terminus');

        $this->retrieve('some terminus')->shouldReturn($terminus);
    }

    function it_throw_exception_when_trying_to_register_same_terminus_twice(Terminus $terminus)
    {
        $this->collect($terminus, 'some terminus');

        $this
            ->shouldThrow('Knp\MinibusBundle\Exception\AlreadyRegisteredTerminusException')
            ->duringCollect($terminus, 'some terminus')
        ;
    }

    function it_throw_an_exception_when_we_retrieve_unexistent_terminus()
    {
        $this
            ->shouldThrow('Knp\MinibusBundle\Exception\UnregisteredTerminusException')
            ->duringRetrieve('unexistent terminus')
        ;
    }
}
