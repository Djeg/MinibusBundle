<?php

namespace Knp\MinibusBundle\Line;

use Knp\MinibusBundle\Registry\StationRegistry;
use Knp\Minibus\Line;
use Knp\MinibusBundle\Minibus\MinibusFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\MinibusBundle\Registry\TerminusRegistry;

/**
 * Create and launch an http line.
 */
class HttpLineLauncher
{
    /**
     * @var StationRegistry $stationRegistry
     */
    private $stationRegistry;

    /**
     * @var TerminusRegistry $terminusRegistry
     */
    private $terminusRegistry;

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
        TerminusRegistry $terminusRegistry,
        Line $line,
        MinibusFactory $minibusFactory = null
    ) {
        $this->stationRegistry  = $stationRegistry;
        $this->terminusRegistry = $terminusRegistry;
        $this->line             = $line;
        $this->minibusFactory   = $minibusFactory ?: new MinibusFactory;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function launch(Request $request)
    {
        $line     = $request->attributes->get('_line');
        $terminus = $request->attributes->get('_terminus');
        $minibus  = $this->minibusFactory->createHttpMinibus($request);

        foreach ($line as $name => $configuration) {
            $station = $this->stationRegistry->retrieve($name);
            $this->line->addStation($station, $configuration);
        }

        foreach ($terminus as $name => $configuration) {
            $terminus = $this->terminusRegistry->retrieve($name);
            $this->line->setTerminus($terminus, $configuration);
        }

        $result = $this->line->lead($minibus);

        if ($result instanceof Response) {
            return $result;
        }

        $minibus->getResponse()->setContent($result);

        return $minibus->getResponse();
    }
}
