<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\DefaultAnalyzer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultAnalyzerTest extends WebTestCase {

    private $DefaultAnalyzer;

    public function __construct() {
        parent::__construct();
        $this->DefaultAnalyzer = new DefaultAnalyzer();
    }

    public function testAnalyze() {
        $this->DefaultAnalyzer->analyze('caca');
        // 
        // 
        // 

        /*  ANALYZE
            -> REVIEW IN
            <- SCORE RESULT OUT

            SCORE RESULT
                {
                    "bathroom": {
                        "score": "1"
                        "foundCriteria": [""]
                    },
                    ...

                }
        */
    }

}
