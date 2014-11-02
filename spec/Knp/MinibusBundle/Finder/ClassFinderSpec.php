<?php

namespace spec\Knp\MinibusBundle\Finder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Finder\Finder;

class ClassFinderSpec extends ObjectBehavior
{
    function let(Finder $finder)
    {
        $this->beConstructedWith($finder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Finder\ClassFinder');
    }

    function it_find_all_the_class_inside_a_directory($finder)
    {
        $finder->files()->willReturn($finder);
        $finder->name('*.php')->willReturn($finder);
        $finder->in(__DIR__)->willReturn([
            new \SplFileInfo(__FILE__)
        ]);

        $this
            ->find(__DIR__, 'spec\Knp\MinibusBundle\Finder')
            ->shouldReturnReflections([
                new \ReflectionClass(__CLASS__)
            ])
        ;
    }

    function it_find_all_classes_that_implements_a_given_interface($finder)
    {
        $finder->files()->willReturn($finder);
        $finder->name('*.php')->willReturn($finder);
        $finder->in(__DIR__)->willReturn([
            new \SplFileInfo(__FILE__)
        ]);

        $this
            ->findImplementation('PhpSpec\ObjectBehavior', __DIR__, 'spec\Knp\MinibusBundle\Finder')
            ->shouldReturnReflections([
                new \ReflectionClass(__CLASS__)
            ])
        ;

        $this
            ->findImplementation('Iterable', __DIR__, 'spec\Knp\MinibusBundle\Finder')
            ->shouldReturnReflections([])
        ;
    }

    function getMatchers()
    {
        return [
            'returnReflections' => function($reflections, $expected) {
                $found = [];
                foreach ($reflections as $reflection) {
                    foreach ($expected as $expect) {
                        if ($expect->getName() === $reflection->getName()) {
                            $found[] = $expect;
                        }
                    }
                }

                foreach ($found as $ref) {
                    $found = false;
                    foreach ($reflections as $reflection) {
                        if ($ref->getName() === $reflection->getName()) {
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        return false;
                    }
                }

                return true;
            }
        ];
    }
}
