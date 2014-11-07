<?php

namespace spec\Knp\MinibusBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PassFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\Compiler\PassFactory');
    }

    function it_create_an_auto_station_registration_pass(Bundle $bundle)
    {
        $this->createAutoStationRegistration($bundle)->shouldHaveType('Knp\MinibusBundle\DependencyInjection\Compiler\AutoRegisterStationPass');
    }
}
