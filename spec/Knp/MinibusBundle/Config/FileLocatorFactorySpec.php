<?php

namespace spec\Knp\MinibusBundle\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileLocatorFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Config\FileLocatorFactory');
    }

    function it_create_instance_of_file_locator()
    {
        $this->create(__DIR__)->shouldHaveType('Symfony\Component\Config\FileLocator');
    }
}
