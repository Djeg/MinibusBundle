<?php

namespace spec\Knp\MinibusBundle\Registry;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\Minibus\Terminus;

class TerminusRegistrySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Registry\TerminusRegistry');
    }

    function it_is_an_iterator()
    {
        $this->shouldHaveType('IteratorAggregate');
    }

    function it_collect_named_terminus(Terminus $terminus)
    {
        $this->collect($terminus, 'some terminus');

        $this->retrieve('some terminus')->shouldReturn($terminus);
    }

    function it_throw_exception_when_trying_to_register_same_terminus_twice(Terminus $terminus)
    {
        $this->collect($terminus, 'some terminus');

        $this
            ->shouldThrow('Knp\MinibusBundle\Exception\AlreadyRegisteredTerminusException')
            ->duringCollect($terminus, 'some terminus')
        ;
    }

    function it_throw_an_exception_when_we_retrieve_unexistent_terminus()
    {
        $this
            ->shouldThrow('Knp\MinibusBundle\Exception\UnregisteredTerminusException')
            ->duringRetrieve('unexistent terminus')
        ;
    }

    function it_iterate_thrue_all_registered_terminus(
        Terminus $terminus1,
        Terminus $terminus2
    ) {
        $this->collect($terminus1, 'first_terminus');
        $this->collect($terminus2, 'second_terminus');

        $this->getIterator()->shouldIterateThrueTerminus([
            'first_terminus'  => $terminus1,
            'second_terminus' => $terminus2
        ]);
    }

    function getMatchers()
    {
        return [
            'iterateThrueTerminus' => function ($iterator, $terminus) {
                $expectedKeys = array_keys($terminus);
                $keys         = array_keys($iterator->getArrayCopy());

                for ($i = 0; $i < count($expectedKeys); $i++) {
                    if ($expectedKeys[$i] !== $keys[$i]) {
                        return false;
                    }

                    if ($iterator[$keys[$i]] !== $terminus[$keys[$i]]) {
                        return false;
                    }
                }

                return true;
            }
        ];
    }
}
