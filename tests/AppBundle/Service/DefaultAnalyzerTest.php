<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\DefaultAnalyzer;
use AppBundle\Service\AnalyzerResponse;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultAnalyzerTest extends WebTestCase {

    private $DefaultAnalyzer;

    public function __construct() {
        parent::__construct();
        $this->DefaultAnalyzer = new DefaultAnalyzer(new AnalyzerResponse());
    }

    public function testDefaultAnalyzer() {
        $review = 'Across the road from Santa Monica Pier is exactly where you want to be when visiting Santa Monica, as well as not far from lots of shops and restaurants/bars. Hotel itself is very new & modern, rooms were great. Comfortable beds & possibly the best shower ever!';
        $result = $this->DefaultAnalyzer->analyze($review)->getFullResults();

        $expectedResult = [

        ];
    }

}
