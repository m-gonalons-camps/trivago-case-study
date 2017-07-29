<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Service\AnalyzerResponse;

class AnalyzeCommand extends ContainerAwareCommand {

    protected function configure() : void {
        $this->setName('app:analyze')
            ->setDescription('Analyzes a single review. Does not store the result anywhere.')
            ->setHelp('This is a command for testing the analyzer. It analyzes the review and returns the score. '. "\n" .
            'The results of the analysis are not being stored in the database.')
            ->addArgument('review', InputArgument::REQUIRED, 'The full review.')
            ->addArgument('library', InputArgument::OPTIONAL, 'Analyzer library. If not specified, the DefaultAnalyzer will be used.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $analyzerLibrary = $input->getArgument('library') ?? 'Default';
        $fullClassPath = 'AppBundle\\Service\\'.$analyzerLibrary.'Analyzer';

        if (class_exists($fullClassPath)) {
            $analyzer = new $fullClassPath(new AnalyzerResponse);
            $result = $analyzer->analyze($input->getArgument('review'));
            $output->writeln(json_encode($result));
        } else {
            $output->writeln('Invalid library: ' . $analyzerLibrary);
        }
    }

}