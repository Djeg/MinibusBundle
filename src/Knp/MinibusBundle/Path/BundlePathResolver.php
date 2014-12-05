<?php

namespace Knp\MinibusBundle\Path;

use Knp\MinibusBundle\Reflection\ReflectionClassFactory;

/**
 * Resolve a path for a given bundle name.
 */
class BundlePathResolver
{
    /**
     * @var array $bundles
     */
    private $bundles;

    /**
     * @var ReflectionClassFactory $reflectionFactory
     */
    private $reflectionFactory;

    /**
     * Must received the symfony parameter "kernel.bundles" wich is formated like :
     * ```yaml
     * SomeBundle: Namepsace\To\SomeBundle
     * SomeOtherBundle: Namepsace\To\SomeOtherBundle
     * ```
     *
     * @param array                  $bundles
     * @param ReflectionClassFactory $reflectionFactory
     */
    public function __construct(array $bundles, ReflectionClassFactory $reflectionFactory = null)
    {
        $this->bundles           = $bundles;
        $this->reflectionFactory = $reflectionFactory ?: new ReflectionClassFactory;
    }

    /**
     * Retrieve a bundle real path. You can pass a string standarly formated
     * like : "@SomeBundle/Resources/foo.yml"
     *
     * @param string $bundlePath
     *
     * @return string the bundle base path
     */
    public function getPath($bundlePath)
    {
        $bundleName = $this->extractBundleName($bundlePath);
        $path       = $this->extractPath($bundlePath);

        if (!isset($this->bundles[$bundleName])) {
            throw new \InvalidArgumentException(sprintf(
                'No bundle named "%s" has been found :-(. Make sure to have it registered inside the AppKernel.',
                $bundleName
            ));
        }

        $reflection = $this->reflectionFactory->create($this->bundles[$bundleName]);

        return dirname($reflection->getFileName()) . $path;
    }

    /**
     * Extract the bundle name from a specialy formated string.
     *
     * @param string $literalString
     *
     * @return string
     */
    public function extractBundleName($literalString)
    {
        $members = explode(DIRECTORY_SEPARATOR, $literalString);

        if ('@' === substr($members[0], 0, 1)) {
            return substr($members[0], 1);
        }

        return $members[0];
    }

    /**
     * Extract a path from a bundle path. ex :
     * @SomeBundle/Resources/foo/bar.xml becomes /Resources/foo/bar.xml
     *
     * @param mixed $bundlePath
     *
     * @return string
     */
    public function extractPath($bundlePath)
    {
        $members = explode(DIRECTORY_SEPARATOR, $bundlePath);
        array_shift($members);

        return DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $members);
    }
}
