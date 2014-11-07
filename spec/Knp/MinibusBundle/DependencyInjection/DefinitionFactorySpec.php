<?php

namespace spec\Knp\MinibusBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Definition;

class DefinitionFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\DefinitionFactory');
    }

    function it_create_an_instance_of_a_dependency_injection_definition()
    {
        $this->create(__CLASS__, ['some arguments'])->shouldReturnDefinitionWith(
            __CLASS__,
            ['some arguments']
        );
    }

    function getMatchers()
    {
        return [
            'returnDefinitionWith' => function ($subject, $class, $arguments) {
                return $subject instanceof Definition &&
                    $subject->getClass() === $class &&
                    $subject->getArguments() === $arguments
                ;
            }
        ];
    }
}
