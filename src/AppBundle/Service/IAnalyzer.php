<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

interface IAnalyzer {

    public function __construct(EntityManagerInterface $em, ?ITypoFixer $tf = NULL);

    public function analyze(string $review) : AnalyzerResponse;

}