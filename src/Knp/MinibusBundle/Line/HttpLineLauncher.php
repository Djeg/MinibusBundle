<?php

namespace Knp\MinibusBundle\Line;

use Knp\MinibusBundle\Registry\StationRegistry;
use Knp\Minibus\Line;
use Knp\MinibusBundle\Exception\InvalidLineException;
use Knp\MinibusBundle\Minibus\MinibusFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Create and launch an http line.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class HttpLineLauncher
{
    /**
     * @var StationRegistry $stationRegistry
     */
    private $stationRegistry;

    /**
     * @var Line $line
     */
    private $line;

    /**
     * @var MinibusFactory $minibusFactory
     */
    private $minibusFactory;

    /**
     * @param StationRegistry $registry
     * @param Line            $line
     * @param MinibusFactory  $minibusFactory
     */
    public function __construct(
        StationRegistry $stationRegistry,
        Line $line,
        MinibusFactory $minibusFactory = null
    ) {
        $this->stationRegistry = $stationRegistry;
        $this->line            = $line;
        $this->minibusFactory  = $minibusFactory ?: new MinibusFactory;
    }

    /**
     * @param Request $request
     *
     * @throws InvalidLineException
     *
     * @return Response
     */
    public function launch(Request $request)
    {
        $line     = $request->attributes->get('_line');
        $minibus  = $this->minibusFactory->createHttpMinibus($request);

        if (null === $line or !is_array($line)) {
            throw new InvalidLineException(sprintf(
                'The line for the route "%s" is not in a valid format.
                Accepted format is a key => value array with station name as
                key and some configuration as a value',
                $request->attributes->get('_route')
            ));
        }

        foreach ($line as $name => $configuration) {
            $station = $this->stationRegistry->retrieve($name);
            $this->line->addStation($station);
        }

        $result = $this->line->lead($minibus);

        if ($result instanceof Response) {
            return $result;
        }

        $minibus->getResponse()->setContent($result);

        return $minibus->getResponse();
    }
}
