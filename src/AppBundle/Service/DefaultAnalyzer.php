<?php

namespace AppBundle\Service;

class DefaultAnalyzer implements IAnalyzer {

    private $AnalyzerResponse;

    public function __construct(AnalyzerResponse $ar) {
        $this->AnalyzerResponse = $ar;
    }

    public function analyze(string $review) : AnalyzerResponse {
        return $this->AnalyzerResponse;
    }

}