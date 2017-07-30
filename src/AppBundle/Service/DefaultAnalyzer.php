<?php

namespace AppBundle\Service;

class DefaultAnalyzer implements IAnalyzer {

    private $AnalyzerResponse;
    private $TypoFixer;

    public function __construct(AnalyzerResponse $ar, ITypoFixer $tf) {
        $this->AnalyzerResponse = $ar;
        $this->TypoFixer = $tf;
    }

    public function analyze(string $review) : AnalyzerResponse {
        $this->TypoFixer->fix($review);

        return $this->AnalyzerResponse;
    }

}