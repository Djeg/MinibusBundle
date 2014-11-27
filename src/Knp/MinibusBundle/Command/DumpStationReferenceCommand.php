<?php

namespace Knp\MinibusBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Knp\Minibus\Config\Configurable;
use Knp\MinibusBundle\Exception\UnregisteredStationException;
use Symfony\Component\Config\Definition\Dumper\XmlReferenceDumper;
use Symfony\Component\Config\Definition\Dumper\YamlReferenceDumper;

/**
 * Dump all available stations or a specific station reference.
 *
 * @author David Jegat <david.jegat@gmail.com>
 */
class DumpStationReferenceCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('knp_minibus:station:dump-reference')
            ->setDescription('Dump available stations or a specific station reference.')
            ->addOption('format', '-f', InputOption::VALUE_OPTIONAL, 'Dump stations in the specific format (yml or xml).', 'yml')
            ->addArgument('station', InputArgument::OPTIONAL, 'A given station alias.', null)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (null === $input->getArgument('station') ) {
            $this->listStations($input, $output);

            return;
        }

        $station = $input->getArgument('station');
        try {
            $station = $this
                ->getContainer()
                ->get('knp_minibus.station_registry')
                ->retrieve($station)
            ;
        } catch (\Exception $e) {
            $output->writeln(sprintf(
                '<error>No station named "%s" has been found :-(.</error>',
                $input->getArgument('station')
            ));

            return;
        }

        $format = $input->getOption('format');

        if ($format === 'yml') {
            $output->writeln(sprintf('# reference of %s', $input->getArgument('station')));
        } else {
            $output->writeln(sprintf('<!-- reference of %s -->', $input->getArgument('station')));
        }

        if (!$station instanceof Configurable) {
            $output->writeln('');

            return;
        }

        switch ($format) {
            case 'yml':
                $dumper = new YamlReferenceDumper();
                break;
            case 'yaml':
                $dumper = new YamlReferenceDumper();
                break;
            case 'xml':
                $dumper = new XmlReferenceDumper();
                break;
            default:
                $output->writeln(sprintf(
                    '<error>Unrecognized format %s, please use yaml or xml.</error>',
                    $format
                ));
                return;
        }

        $output->writeln($dumper->dump($station->getConfiguration()));
    }

    private function listStations(InputInterface $input, OutputInterface $output)
    {
        $table = $this->getHelper('table');
        $table->setHeaders(['Terminus', 'Namespace', 'Configurable']);

        $rows = [];

        foreach ($this->getContainer()->get('knp_minibus.station_registry') as $alias => $station) {
            $rows[] = [
                $alias,
                get_class($station),
                $station instanceof Configurable ? 'Yes' : 'No'
            ];
        }

        $table->setRows($rows);

        $table->render($output);
    }
}
