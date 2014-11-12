<?php

namespace spec\Knp\MinibusBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\MinibusBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Knp\MinibusBundle\DependencyInjection\XmlFileLoaderFactory;
use Knp\MinibusBundle\Config\FileLocatorFactory;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocatorInterface;

class KnpMinibusExtensionSpec extends ObjectBehavior
{
    function let(
        Configuration $configuration,
        Processor $processor,
        XmlFileLoaderFactory $loaderFactory,
        FileLocatorFactory $locatorFactory
    ) {
        $this->beConstructedWith($configuration, $processor, $loaderFactory, $locatorFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\KnpMinibusExtension');
    }

    function it_is_a_dependency_injection_extension()
    {
        $this->shouldHaveType('Symfony\Component\DependencyInjection\Extension\Extension');
    }

    function it_process_configuration_and_load_services_files(
        $configuration,
        $processor,
        $loaderFactory,
        $locatorFactory,
        XmlFileLoader $loader,
        FileLocatorInterface $locator,
        ContainerBuilder $container
    ) {
        $locatorFactory->create(Argument::any())->willReturn($locator);
        $loaderFactory->create($container, $locator)->willReturn($loader);

        $processor->processConfiguration($configuration, ['configuration'])->willReturn([]);

        $loader->load('services.xml')->shouldBeCalled();

        $this->load(['configuration'], $container);
    }
}
