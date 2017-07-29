<?php

namespace AppBundle\Service;

interface IAnalyzer {

    public function __construct(AnalyzerResponse $ar);

    public function analyze(string $review) : AnalyzerResponse;

}