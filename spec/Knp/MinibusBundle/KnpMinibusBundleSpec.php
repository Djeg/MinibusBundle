<?php

namespace spec\Knp\MinibusBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class KnpMinibusBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\KnpMinibusBundle');
    }

    function it_is_a_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }
}
