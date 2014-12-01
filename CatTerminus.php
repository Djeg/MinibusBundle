<?php

namespace My\Bundle\Station;

use Knp\Minibus\Station;
use Knp\Minibus\Minibus;

class MyStation implements Station
{
    public function handle(Minibus $minibus, array $configuration)
    {
        $someConfig = $configuration['some_key'];
    }
}
