<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity;

class DBPopulationCommand extends ContainerAwareCommand {

    private $doctrineManager;

    protected function configure() : void {
        $this->setName('db:populate')
            ->setDescription('Populates the criteria, topics, topics_aliases and analysis_libraries tables.')
            ->setHelp('This command will populate the tables criteria, topics, topics_aliases and '.
            'analysis_libraries with the default and minimum data needed for running the application.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void {
        // TODO: Check if the tables are empty. If they are not empty, do nothing.

        $this->doctrineManager = $this->getContainer()->get('doctrine')->getManager();

        $this->generateTopics();
        $this->generateCriteria();
        $this->generateAnalysisLibraries();
    }

    private function generateTopics() {
        // TODO: Retrieve these topics from a file or somewhere else instead of hardcoding them here?
        // I'm thinking about a JSON file stored in AppBundle/Command/data.json and save everything there (criteria, topics, etc)

        $topics = [
            'room' => ['apartment', 'chamber'],
            'hotel' => ['property', 'lodge', 'resort'],
            'staff' => ['service', 'personnel', 'crew', 'he', 'she'],
            'location' => ['spot'],
            'breakfast' => [],
            'bed' => ['sleep quality', 'mattresses', 'linens'],
            'food' => ['dinner', 'lunch'],
            'bathroom' => ['lavatory', 'shower', 'toilet', 'bath'],
            'restaurant' => [],
            'pool' => ['spa', 'wellness'],
            'bar' => []
        ];

        foreach ($topics as $topic => $aliases) {
            $topicEntity = new Entity\Topic();

            $topicEntity->setName($topic);
            $this->doctrineManager->persist($topicEntity);

            foreach ($aliases as $alias) {
                $aliasEntity = new Entity\TopicAlias();

                $aliasEntity->setAlias($alias);
                $aliasEntity->setTopic($topicEntity);
                $this->doctrineManager->persist($aliasEntity);
            }
        }

        $this->doctrineManager->flush();
    }
}