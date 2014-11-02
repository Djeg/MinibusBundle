<?php

namespace spec\Knp\MinibusBundle\Bundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MinibusBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Bundle\MinibusBundle');
    }

    function it_is_a_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }
}
