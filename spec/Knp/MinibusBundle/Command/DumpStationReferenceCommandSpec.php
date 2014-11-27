<?php

namespace spec\Knp\MinibusBundle\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DumpStationReferenceCommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Command\DumpStationReferenceCommand');
    }

    function it_is_a_command()
    {
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }
}
