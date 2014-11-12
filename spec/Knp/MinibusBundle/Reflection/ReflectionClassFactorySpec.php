<?php

namespace spec\Knp\MinibusBundle\Reflection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReflectionClassFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Reflection\ReflectionClassFactory');
    }

    function it_create_a_reflection_class_with_the_given_namespace()
    {
        $this->create(__CLASS__)->shouldReturnReflectionOfMySelf();
    }

    function getMatchers()
    {
        return [
            'returnReflectionOfMySelf' => function ($reflection) {
                return $reflection instanceof \ReflectionClass &&
                    $reflection->getName() === __CLASS__
                ;
            }
        ];
    }
}
