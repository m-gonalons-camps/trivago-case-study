<?php

namespace Tests\AppBundle\Command;

use AppBundle\Command\DBPopulationCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class DBPopulationCommandTest extends KernelTestCase {

    private $application;
    private $command;
    private $commandTester;
    private $doctrineManager;

    private $entitiesToCheck = [
        "AppBundle:Topic",
        "AppBundle:TopicAlias",
        "AppBundle:Criteria",
        "AppBundle:AnalysisLibrary"
    ];

    public function testExecute() : void {
        $this->initializations();
        
        if ($this->areTablesEmpty()) {
            $this->commandTester->execute(['command' => $this->command->getName()]);
            $this->assertEquals("Success.\n", $this->commandTester->getDisplay());
        } else {
            $this->commandTester->execute(['command' => $this->command->getName()]);
            $this->assertNotEquals("Success.\n", $this->commandTester->getDisplay());
        }

    }


    private function initializations() : void {
        self::bootKernel();
        $this->application = new Application(self::$kernel);
        $this->application->add(new DBPopulationCommand());
        $this->command = $this->application->find('db:populate');
        $this->commandTester = new CommandTester($this->command);

        $this->doctrineManager = static::$kernel->getContainer()->get('doctrine')->getManager();
    }


    private function areTablesEmpty() : bool {
        $genericRepository = static::$kernel->getContainer()->get('AppBundle.genericRepository');
        return $genericRepository->areTablesEmpty($this->entitiesToCheck);
    }

}
