<?php

namespace spec\Knp\MinibusBundle\Path;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\MinibusBundle\Reflection\ReflectionClassFactory;

class BundlePathResolverSpec extends ObjectBehavior
{
    function let(ReflectionClassFactory $reflectionFactory)
    {
        $this->beConstructedWith([
            'SomeBundle' => 'Symfony\Component\HttpKernel\Bundle\Bundle'
        ], $reflectionFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Path\BundlePathResolver');
    }

    function it_return_a_bundle_path(
        $reflectionFactory,
        \ReflectionClass $reflection
    ) {
        $reflectionFactory->create('Symfony\Component\HttpKernel\Bundle\Bundle')->willReturn($reflection);
        $reflection->getFileName()->willReturn('/path/to/symfony/component/HttpKernel/Bundle/Bundle.php');

        $this->getPath('@SomeBundle/Resources/foo/bar.xml')->shouldReturn('/path/to/symfony/component/HttpKernel/Bundle/Resources/foo/bar.xml');
    }

    function it_throw_exception_if_the_bundle_is_not_registred()
    {
        $this->shouldThrow('InvalidArgumentException')->duringGetPath('InvalidBundle');
    }
}
