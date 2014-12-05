<?php

namespace Knp\MinibusBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Knp\Minibus\Configurable;
use Knp\MinibusBundle\Exception\UnregisteredTerminusException;
use Symfony\Component\Config\Definition\Dumper\YamlReferenceDumper;
use Symfony\Component\Config\Definition\Dumper\XmlReferenceDumper;

/**
 * Dump available terminus or one terminus reference.
 */
class DumpTerminusReferenceCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName('knp_minibus:terminus:dump-reference')
            ->setDescription('Dump available terminus or one specific terminus reference.')
            ->addOption('format', '-f', InputOption::VALUE_OPTIONAL, 'Dump a terminus in the speficifc format (yml or xml).', 'yml')
            ->addArgument('terminus', InputArgument::OPTIONAL, 'A given terminus.', null)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (null === $input->getArgument('terminus')) {
            $this->listTerminus($output);

            return;
        }

        $terminus = $input->getArgument('terminus');
        try {
            $terminus = $this
                ->getContainer()
                ->get('knp_minibus.terminus_registry')
                ->retrieve($terminus)
            ;
        } catch (UnregisteredTerminusException $e) {
            $output->writeln(sprintf(
                '<error>No terminus named "%s" has been found :-(.</error>',
                $input->getArgument('terminus')
            ));

            return;
        }

        $format = $input->getOption('format');

        if ($format === 'yml') {
            $output->writeln(sprintf('# reference of %s', $input->getArgument('terminus')));
        } else {
            $output->writeln(sprintf('<!-- reference of %s -->', $input->getArgument('terminus')));
        }

        if (!$terminus instanceof Configurable) {
            $output->writeln('');

            return;
        }

        switch ($format) {
            case 'yml':
                $dumper = new YamlReferenceDumper;
                break;
            case 'yaml':
                $dumper = new YamlReferenceDumper;
                break;
            case 'xml':
                $dumper = new XmlReferenceDumper;
                break;
            default:
                $output->writeln(sprintf(
                    '<error>Unrecognized format %s, please use yaml or xml.</error>',
                    $format
                ));
                return;
        }

        $output->writeln($dumper->dump($terminus->getConfiguration()));
    }

    public function listTerminus(OutputInterface $output)
    {
        $table = $this->getHelper('table');
        $table->setHeaders(['Terminus', 'Namespace', 'Configurable']);

        $rows = [];

        foreach ($this->getContainer()->get('knp_minibus.terminus_registry') as $alias => $terminus) {
            $rows[] = [
                $alias,
                get_class($terminus),
                $terminus instanceof Configurable ? 'Yes' : 'No'
            ];
        }

        $table->setRows($rows);

        $table->render($output);
    }
}
