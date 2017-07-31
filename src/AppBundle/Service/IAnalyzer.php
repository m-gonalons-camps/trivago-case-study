<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

interface IAnalyzer {

    public function __construct(AnalyzerResponse $ar, ITypoFixer $tf, EntityManagerInterface $em);

    public function analyze(string $review) : AnalyzerResponse;

}