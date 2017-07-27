<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

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

    protected function execute(InputInterface $input, OutputInterface $output) : void {
        // TODO: Check if the tables are empty. If they are not empty, do nothing.
        if (! file_exists($this->dbDataFilePath)) {
            throw new \Exception($this->dbDatafilePath . ' file does not exist.');
        }

        // CHECK IF VALID JSON
        // IF INVALID, EXCEPTION
        $this->jsonData = json_decode(file_get_contents($this->dbDataFilePath));

        $this->doctrineManager = $this->getContainer()->get('doctrine')->getManager();

        $this->generateTopics();
        $this->generateCriteria();
        $this->generateAnalysisLibraries();

        $this->doctrineManager->flush();
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