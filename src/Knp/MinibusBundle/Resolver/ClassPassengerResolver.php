<?php

namespace Knp\MinibusBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;

/**
 * Resolve a passenger as an array with the special 'class' key.
 */
class ClassPassengerResolver implements Resolver
{
    /**
     * @var ArgumentResolver $resolver
     */
    private $resolver;

    /**
     * @param ArgumentResolver $resolver
     */
    public function __construct(ArgumentResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($subject, Request $request)
    {
        $class     = $this->validateClass($subject['class']);
        $method    = isset($subject['method']) ? $subject['method'] : null;
        $arguments = isset($subject['arguments']) ? $subject['arguments'] : [];

        foreach ($arguments as &$argument) {
            $argument = $this->resolver->resolve($argument, $request);
        }

        if (null === $method) {
            return (new \ReflectionClass($class))->newInstanceArgs($arguments);
        }

        $method = new \ReflectionMethod($class, $method);

        if (!$method->isStatic() or !$method->isPublic()) {
            throw new \InvalidArgumentException(sprintf(
                'The %s::%s must be public and static in order to be used as a static constructor.',
                $class,
                (string)$method
            ));
        }

        return call_user_func_array([$class, (string)$method], $arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($subject, Request $request)
    {
        return is_array($subject) && isset($subject['class']);
    }

    /**
     * @param string $class
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    private function validateClass($class)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf(
                'The class "%s" does not exists. Maybe a mistatting in your routing file :-/.',
                $class
            ));
        }

        return $class;
    }
}
