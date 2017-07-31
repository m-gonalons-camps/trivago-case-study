<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\DefaultAnalyzer;
use AppBundle\Service\AnalyzerResponse;
use AppBundle\Service\TypoFixer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultAnalyzerTest extends WebTestCase {

    private $DefaultAnalyzer;

    public function __construct() {
        parent::__construct();
        self::bootKernel();
        $this->DefaultAnalyzer = new DefaultAnalyzer(
            new AnalyzerResponse(),
            new TypoFixer(),
            static::$kernel->getContainer()->get('doctrine')->getManager()
        );
    }

    public function testDefaultAnalyzer() {
        $this->_testSimpleReview();
        $this->_testReviewWithTypos();
    }

    private function _testSimpleReview() {
        $review = 'The room was great and the staff was nice. The restaurant was not bad. The room is also very spacious.';
        $result = $this->DefaultAnalyzer->analyze($review)->getFullResults();

        $expectedResult = [
            'room' => [
                'score' => 200,
                'criteria' => ['great', 'spacious']
            ],
            'staff' => [
                'score' => 100,
                'criteria' => ['nice']
            ],
            'restaurant' => [
                'score' => 0,
                'criteria' => ['not bad']
            ]
        ];

        $this->assertEquals(json_encode($expectedResult), json_encode($result));
    }

    private function _testReviewWithTypos() {
        $review = 'The restaurnt is fantassticc!';
        $result = $this->DefaultAnalyzer->analyze($review)->getFullResults();

        $expectedResult = [
            'restaurant' => [
                'score' => 100,
                'criteria' => ['fantastic']
            ]
        ];

        $this->assertEquals(json_encode($expectedResult), json_encode($result));
    }

}
