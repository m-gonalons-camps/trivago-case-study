<?php

namespace AppBundle\Service;

interface IAnalyzer {

    public function __construct(AnalyzerResponse $ar, ITypoFixer $tf);

    public function analyze(string $review) : AnalyzerResponse;

}