<?php

namespace Knp\MinibusBundle\Utils;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\Common\Inflector\Inflector;

/**
 * Group all the naming strategy of minibus into statical methods.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class NamingStrategist
{
    /**
     * This method will dotify a given namespace. For example the following 
     * namespace : Foo\Bar\BazClass will be transformed into foo.bar.baz_class.
     * You can precise a suffix to remove on the last member.
     *
     * @param string  $subject
     * @param string  $suffix
     * @param boolean $replaceOnlyLastMember
     *
     * @return string
     */
    public static function dotify($subject, $suffix = null, $replaceOnlyLastMember = false)
    {
        $explodedNamespace = explode('\\', $subject);

        array_walk($explodedNamespace, function (&$member, $key) use (
            $suffix,
            $explodedNamespace,
            $replaceOnlyLastMember
        ) {
            if (null !== $suffix) {
                if ($replaceOnlyLastMember) {
                    if ($key === count($explodedNamespace) - 1) {
                        $member = str_replace($suffix, '', $member);
                    }
                } else {
                    $member = str_replace($suffix, '', $member);
                }
            }

            $member = Inflector::tableize($member);
        });

        return implode('.', $explodedNamespace);
    }

    /**
     * Deduce a bundle alias. By default if the bundle possess an extension it
     * will use the bundle extension alias, else it will tableize the bundle
     * namespace and remove the Bundle suffix.
     *
     * @param Bundle $bundle
     *
     * @return null
     */
    public static function aliasify(Bundle $bundle)
    {
        if (null !== $extension = $bundle->getContainerExtension()) {
            return $extension->getAlias();
        }

        return str_replace('.', '_', self::dotify($bundle->getNamespace(), 'Bundle', true));
    }

    /**
     * It will servicify a given subject depending on the given bundle alias.
     * For exemple:
     * The following bundle: Foo\TestBundle\FooTestBundle
     * The given class: Foo\TestBundle\Some\Service
     * will became: foo_test.some.service
     *
     * @param string|object $subject
     * @param Bundle        $bundle
     * @param string        $suffx
     *
     * @return string
     */
    public static function servicify($subject, Bundle $bundle, $suffix = null)
    {
        $alias = self::aliasify($bundle);

        $subject = str_replace($bundle->getNamespace(). '\\', '', is_object($subject) ?
            (new \ReflectionClass($subject))->getName() :
            $subject
        );

        return sprintf('%s.%s', $alias, self::dotify($subject, $suffix, true));
    }

    /**
     * Stationify a service. It means that the namespace station is removed
     * and the the result is servicify. It result to something like this:
     *
     * `MyBundle\Station\SomeStation`    => `my_bundle.some`
     * `MyBundle\Station\Sub\SubStation` => `my_bundle.sub.sub`
     *
     * @param string|object $station
     * @param Bundle        $bundle
     *
     * @return string
     */
    public static function stationify($station, Bundle $bundle)
    {
        $station = is_object($station) ?
            (new \RefletionClass($station))->getName() :
            $station
        ;

        return self::servicify(
            str_replace('\\Station\\', '\\', $station),
            $bundle,
            'Station',
            true
        );
    }

    /**
     * Terminusify a service. It means that the namespace `Terminus` is removed
     * and the result is servicify, example:
     *
     * `MyBundle\Terminus\SomeTerminus`     => `my_bundle.some`
     * `MyBundle\Terminus\Sub\SomeTerminus` => `my_bundle.sub.some`
     *
     * @param string|object $terminus
     * @param Bundle        $bundle
     *
     * @return string
     */
    public static function terminusify($terminus, Bundle $bundle)
    {
        $terminus = is_object($terminus) ?
            (new \ReflectionClass($terminus))->getName() :
            $terminus
        ;

        return self::servicify(
            str_replace('\\Terminus\\', '\\', $terminus),
            $bundle,
            'Terminus',
            true
        );
    }
}
