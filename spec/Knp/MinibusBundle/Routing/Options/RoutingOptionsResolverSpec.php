<?php

namespace spec\Knp\MinibusBundle\Routing\Options;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RoutingOptionsResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\MinibusBundle\Routing\Options\RoutingOptionsResolver');
    }

    function it_resolve_routing_options(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['pattern', 'line', 'terminus'])->willReturn($resolver);
        $resolver->setDefaults([
            'method'       => 'GET',
            'format'       => 'html',
            'condition'    => null,
            'host'         => null,
            'scheme'       => null,
            'defaults'     => [],
            'requirements' => [],
            'passengers'   => []
        ])->willReturn($resolver);
        $resolver->setAllowedTypes([
            'pattern'      => 'string',
            'line'         => 'array',
            'terminus'     => 'array',
            'method'       => ['string', 'array'],
            'format'       => 'string',
            'condition'    => ['string', 'null'],
            'host'         => ['string', 'null'],
            'scheme'       => ['string', 'null'],
            'defaults'     => 'array',
            'requirements' => 'array',
            'passengers'   => 'array'
        ])->willReturn($resolver);
        $resolver->setNormalizers(Argument::that(function ($values) {
            if (!is_array($values)) {
                return false;
            }

            $normalizers = ['line', 'terminus', 'passengers'];

            foreach ($normalizers as $normalizer) {
                if (!array_key_exists($normalizer, $values)) {
                    return false;
                }

                if(!$values[$normalizer] instanceof \Closure) {
                    return false;
                }
            }

            return true;
        }))->willReturn($resolver);

        $resolver->resolve(['not resolved parameters'])->willReturn('resolved parameters');
        $this->resolve(['not resolved parameters'], $resolver)->shouldReturn('resolved parameters');
    }
}
