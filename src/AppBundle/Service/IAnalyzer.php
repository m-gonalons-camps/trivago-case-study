<?php

namespace AppBundle\Service;

interface IAnalyzer {

    public function analyze(string $review) : AnalyzerResponse;

}