<?php

namespace spec\Knp\MinibusBundle\Yaml;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class YamlParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Yaml\YamlParser');
    }

    function it_return_a_yaml_content()
    {
        $this->parse(__DIR__ .'/../../../../example/Resources/test.yml')->shouldReturn([
            'foo' => [
                'bar' => 'baz'
            ]
        ]);
    }
}
