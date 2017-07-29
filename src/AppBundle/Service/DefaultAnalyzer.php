<?php

namespace AppBundle\Service;

final class DefaultAnalyzer implements IAnalyzer {

    public function analyze(string $review) : AnalyzerResponse {
        $response = new AnalyzerResponse();
        return $response;
    }

}