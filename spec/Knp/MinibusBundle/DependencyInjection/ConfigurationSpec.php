<?php

namespace spec\Knp\MinibusBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\Minibus\Config\TreeBuilderFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class ConfigurationSpec extends ObjectBehavior
{
    function let(TreeBuilderFactory $factory)
    {
        $this->beConstructedWith($factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\Configuration');
    }

    function it_is_a_dependency_injection_configuration()
    {
        $this->shouldHaveType('Symfony\Component\Config\Definition\ConfigurationInterface');
    }

    function it_configure_and_return_a_tree_builder($factory)
    {
        $treeBuilder = new TreeBuilder;
        $factory->create()->willReturn($treeBuilder);

        $this->getConfigTreeBuilder()->shouldReturn($treeBuilder);
    }
}
