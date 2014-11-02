<?php

namespace spec\Knp\MinibusBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocatorInterface;

class XmlFileLoaderFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\XmlFileLoaderFactory');
    }

    function it_create_new_instance_of_an_xml_file_loader(ContainerBuilder $container, FileLocatorInterface $locator)
    {
        $this->create($container, $locator)->shouldHaveType('Symfony\Component\DependencyInjection\Loader\XmlFileLoader');
    }
}
