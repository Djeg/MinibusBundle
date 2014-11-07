<?php

namespace spec\Knp\MinibusBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Reference;

class ReferenceFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\DependencyInjection\ReferenceFactory');
    }

    function it_ceate_a_reference()
    {
        $this->create('some_id')->shouldReturnNewReference('some_id');
    }

    function getMatchers()
    {
        return [
            'returnNewReference' => function ($subject, $id) {
                return is_object($subject) &&
                    $subject instanceof Reference &&
                    (string)$subject === $id
                ;
            }
        ];
    }
}
