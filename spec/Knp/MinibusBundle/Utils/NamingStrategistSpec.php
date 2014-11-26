<?php

namespace spec\Knp\MinibusBundle\Utils;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Knp\MinibusBundle\Utils\NamingStrategist;

class NamingStrategistSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Utils\NamingStrategist');
    }

    function it_dotify_a_namespace()
    {
        expect(NamingStrategist::dotify('Foo\Bar\SomeClassSuffixed', 'Suffixed'))->toBe('foo.bar.some_class');
    }

    function it_deduce_a_bundle_alias(Bundle $bundle, Extension $extension)
    {
        $bundle->getContainerExtension()->willReturn($extension);
        $extension->getAlias()->willReturn('some_alias');

        expect(NamingStrategist::aliasify($bundle->getWrappedObject()))->toBe('some_alias');

        $bundle = new FakeBundle;

        expect(NamingStrategist::aliasify($bundle))->toBe('spec_knp_minibus_bundle_utils');
    }

    function it_servicify_class_or_namespace_of_the_given_bundle()
    {
        $bundle = new FakeBundle;
        $class = 'spec\Knp\MinibusBundle\Utils\SomePrefixed\ServiceNamePrefixed';

        expect(NamingStrategist::servicify($class, $bundle, 'Prefixed'))
            ->toBe('spec_knp_minibus_bundle_utils.some_prefixed.service_name')
        ;
    }

    function it_stationify_a_given_class_or_station()
    {
        $bundle = new FakeBundle;
        $station = 'spec\Knp\MinibusBundle\Utils\Station\Some\SubStation';

        expect(NamingStrategist::stationify($station, $bundle))
            ->toBe('spec_knp_minibus_bundle_utils.some.sub')
        ;
    }

    function it_terminusify_a_given_class_or_terminus()
    {
        $bundle   = new FakeBundle;
        $terminus = 'spec\Knp\MinibusBundle\Utils\Terminus\Some\SubTerminus';

        expect(NamingStrategist::terminusify($terminus, $bundle))
            ->toBe('spec_knp_minibus_bundle_utils.some.sub')
        ;
    }
}

class FakeBundle extends Bundle {};

