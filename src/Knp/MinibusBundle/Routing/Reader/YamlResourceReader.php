<?php

namespace Knp\MinibusBundle\Routing\Reader;

use Knp\MinibusBundle\Path\BundlePathResolver;
use Knp\MinibusBundle\Yaml\YamlParser;
use Knp\MinibusBundle\Exception\MisstatedRouteException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use Knp\MinibusBundle\Routing\Options\RoutingOptionsResolver;

/**
 * It read a yaml routing ressource and also resolve the content.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class YamlResourceReader
{
    /**
     * @var BundlePathResolver $pathResolver
     */
    private $pathResolver;

    /**
     * @var YamlParser $parser
     */
    private $parser;

    /**
     * @var RoutingOptionsResolver $resolver
     */
    private $resolver;

    /**
     * @param BundlePathResolver     $pathResolver
     * @param YamlParser             $parser
     * @param RoutingOptionsResolver $resolver
     */
    public function __construct(
        BundlePathResolver     $pathResolver,
        YamlParser             $parser = null,
        RoutingOptionsResolver $resolver = null
    ) {
        $this->pathResolver = $pathResolver;
        $this->parser       = $parser ?: new YamlParser;
        $this->resolver     = $resolver ?: new RoutingOptionsResolver;
    }

    /**
     * Read a resource and "resolve" or "validate" it's content.
     *
     * @param string $resource
     *
     * @throws MisstatedRouteException
     *
     * @return array the parsed resource
     */
    public function read($resource)
    {
        $path      = $this->pathResolver->getPath($resource);
        $rawRoutes = $this->parser->parse($path);
        $routes    = [];

        $rawRoutes = is_array($rawRoutes) ? $rawRoutes : (array)$rawRoutes;

        foreach ($rawRoutes as $name => $attributes) {
            try {
                $validatedAttributes = $this->resolver->resolve($attributes);
            } catch (\Exception $reason) {
                throw new MisstatedRouteException($name, $reason);
            }

            $routes[$name] = $validatedAttributes;
        }

        return $routes;
    }
}
