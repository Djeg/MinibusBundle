<?php

namespace Knp\MinibusBundle\Yaml;

use Symfony\Component\Yaml\Yaml;

/**
 * Parse a YAML file. It's a simple wrapper for the symfony Yaml::parse method.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class YamlParser
{
    /**
     * @param string $yamlFile
     *
     * @return array
     */
    public function parse($yamlFile)
    {
        return Yaml::parse($yamlFile);
    }
}
