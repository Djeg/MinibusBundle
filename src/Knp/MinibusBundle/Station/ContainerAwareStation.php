<?php

namespace Knp\MinibusBundle\Station;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Knp\Minibus\Station;

/**
 * A simple interface symbolising a container injectable station ready for
 * many sugar :)
 */
interface ContainerAwareStation extends Station, ContainerAwareInterface
{
}
