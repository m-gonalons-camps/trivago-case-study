<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class DefaultAnalyzer implements IAnalyzer {

    private $AnalyzerResponse;
    private $TypoFixer;
    private $DoctrineManager;

    private $criteria = [];
    private $topics = [];

    public function __construct(AnalyzerResponse $ar, ITypoFixer $tf, EntityManagerInterface $em) {
        $this->AnalyzerResponse = $ar;
        $this->TypoFixer = $tf;
        $this->DoctrineManager = $em;

        $this->setCriteria()->setTopics();
    }

    public function analyze(string $review) : AnalyzerResponse {
        $this->TypoFixer->fix($review);

        return $this->AnalyzerResponse;
    }

    private function setCriteria() : DefaultAnalyzer {
        $this->criteria = $this->DoctrineManager->getRepository('AppBundle:Criteria')->findAll();
        return $this;
    }

    private function setTopics() : DefaultAnalyzer {
        $this->topics = $this->DoctrineManager->getRepository('AppBundle:Topic')->findAll();
        return $this;
    }

}