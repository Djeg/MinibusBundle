<?php

namespace Knp\MinibusBundle\Routing\Options;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Resolve a minibus routes options.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class RoutingOptionsResolver
{
    /**
     * @param array                    $attributes
     * @param OptionsResolverInterface $resolver
     *
     * @return array the resolved attributes.
     */
    public function resolve(array $attributes, OptionsResolverInterface $resolver = null)
    {
        $resolver = $resolver ?: new OptionsResolver;

        $this
            ->setRequired($resolver)
            ->setDefaults($resolver)
            ->setAllowedTypes($resolver)
            ->setNormalizers($resolver)
        ;

        return $resolver->resolve($attributes);
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return RoutingOptionsResolver
     */
    private function setRequired(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['pattern', 'line', 'terminus']);

        return $this;
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return RoutingOptionsResolver
     */
    private function setDefaults(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'method'       => 'GET',
            'format'       => 'html',
            'condition'    => null,
            'host'         => null,
            'scheme'       => null,
            'defaults'     => [],
            'requirements' => [],
            'passengers'   => []
        ]);

        return $this;
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return RoutingOptionsResolver
     */
    private function setAllowedTypes(OptionsResolverInterface $resolver)
    {
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
        ]);

        return $this;
    }

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return RoutingOptionsResolver
     */
    private function setNormalizers(OptionsResolverInterface $resolver)
    {
        $resolver->setNormalizers([
            'line'       => $this->getLineNormalizer(),
            'terminus'   => $this->getTerminusNormalizer(),
            'passengers' => $this->getPassengerNormalizer(),
        ]);

        return $this;
    }

    /**
     * @return \Closure
     */
    private function getLineNormalizer()
    {
        return function (Options $options, $lines) {
            if (!is_array($lines)) {
                return [$lines => []];
            }

            return array_map(function ($config) { 
                return is_array($config) ? $config : (array)$config;
            }, $lines);
        };
    }

    /**
     * @return \Closure
     */
    private function getTerminusNormalizer()
    {
        return function (Options $options, $terminus) {
            if (!is_array($terminus)) {
                throw new InvalidArgumentException('A terminus must be an array.');
            }

            $terminusName = null;
            $config       = null;
            foreach ($terminus as $name => $configuration) {
                $terminusName = $name;
                $config       = is_array($configuration) ?
                    $configuration :
                    []
                ;
            }

            return [$terminusName => $config];
        };
    }

    /**
     * @return \Closure
     */
    private function getPassengerNormalizer()
    {
        return function (Options $options, $passengers) {
            return is_array($passengers) ? $passengers : [$passengers => ''];
        };
    }
}
