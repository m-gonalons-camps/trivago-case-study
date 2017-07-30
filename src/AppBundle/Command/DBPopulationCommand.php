<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity;

class DBPopulationCommand extends ContainerAwareCommand {

    private $doctrineManager;
    private $dbDataFilePath = __DIR__ . '/DBData.json';
    private $jsonData;

    protected function configure() : void {
        $this->setName('db:populate')
            ->setDescription('Populates the criteria, topics, topics_aliases and analysis_libraries tables.')
            ->setHelp('This command will populate the tables criteria, topics, topics_aliases and '.
            'analysis_libraries with the default and minimum data needed for running the application.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->doctrineManager = $this->getContainer()->get('doctrine')->getManager();
        if (! $this->initializationChecks($output)) return NULL;

        $this->generateTopics();
        $this->generateCriteria();
        $this->generateAnalysisLibraries();

        $this->doctrineManager->flush();

        $output->writeln('Success.');
    }

    private function initializationChecks(OutputInterface $output) : bool {
        if (! $this->areTablesEmpty()) {
            $output->writeln('Tables are not empty; nothing has changed.');
            return FALSE;
        }

        if (! file_exists($this->dbDataFilePath)) {
            $output->writeln($this->dbDatafilePath . ' file does not exist.');
            return FALSE;
        }

        try {
            $this->jsonData = json_decode(file_get_contents($this->dbDataFilePath));
        } catch (\Exception $e) {
            $output->writeln('The file ' . $this->dbDataFilePath . ' does not contain a valid JSON.');
            return FALSE;
        }

        return TRUE;
    }

    private function areTablesEmpty() : bool {
        $genericRepository = $this->getApplication()->getKernel()->getContainer()->get('AppBundle.GenericRepository');

        return $genericRepository->areTablesEmpty([
            "AppBundle:Topic",
            "AppBundle:TopicAlias",
            "AppBundle:Criteria",
            "AppBundle:AnalysisLibrary"
        ]);
    }

    private function generateTopics() : void {
        foreach ($this->jsonData->topics as $topicName => $topicAliases) {
            $topicEntity = new Entity\Topic();
            $topicEntity->setName($topicName);
            
            $this->doctrineManager->persist($topicEntity);
            foreach ($topicAliases as $alias) {
                $aliasEntity = new Entity\TopicAlias();
                $aliasEntity->setAlias($alias);
                $aliasEntity->setTopic($topicEntity);

                $this->doctrineManager->persist($aliasEntity);
            }
        }
    }

    private function generateCriteria() : void {
        foreach ($this->jsonData->criteria as $keyword => $score) {
            $criteriaEntity = new Entity\Criteria();
            $criteriaEntity->setKeyword($keyword);
            $criteriaEntity->setScore($score);
            
            $this->doctrineManager->persist($criteriaEntity);
        }
    }

    private function generateAnalysisLibraries() : void {
        foreach ($this->jsonData->analysisLibraries as $libraryName) {
            $libraryEntity = new Entity\AnalysisLibrary;
            $libraryEntity->setName($libraryName);
            $this->doctrineManager->persist($libraryEntity);
        }
    }
}