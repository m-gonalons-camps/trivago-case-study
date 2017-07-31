<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\DefaultAnalyzer;
use AppBundle\Service\TypoFixer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultAnalyzerTest extends WebTestCase {

    private $DefaultAnalyzer;

    public function __construct() {
        parent::__construct();
        self::bootKernel();
        $this->DefaultAnalyzer = new DefaultAnalyzer(
            new TypoFixer(),
            static::$kernel->getContainer()->get('doctrine')->getManager()
        );
    }

    public function testDefaultAnalyzer() {
        $testCases = $this->getTestCases();

        foreach ($testCases as $testCase) {
            $result = $this->DefaultAnalyzer->analyze($testCase['review'])->getFullResults();
            $this->assertEquals(json_encode($testCase['expectedResult']), json_encode($result));
        }
    }


    private function getTestCases() {
        return [[
            'review' => 'The room was great and the staff was nice. The restaurant was not bad. The room is also very spacious.',
            'expectedResult' => [
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
            ]
        ],[
            'review' => 'The restaurnt is fantassticc!',
            'expectedResult' => [
                'restaurant' => [
                    'score' => 100,
                    'criteria' => ['fantastic']
                ]
            ]
        ],[
            'review' => 'Most friendly and helpful receptionist ever, so lovely and great first impression of hotel. '.
                        'Couldn\'t have been more sweet, giving me directions to a function I was attending along '.
                        'with a helpful map, ensuring parking in car park and facilitating an early check in.'.
                        ' If nothing else had gone right with hotel this lady ensured I would have left with a good impression. '.
                        'Fortunately everything about the hotel was exceptional, and I don\'t give praise lightly. '.
                        'It was clean, stylish, roomy with excellent service in both bar where we had lunch and restaurant'.
                        ' where we had dinner. Food was beyond good and great value for money and service in both places '.
                        'attentive and efficient. Room itself was well equipped and comfortable. '.
                        'I could go on but suffice it to say I was very pleased with my stay, and although short and sweet this time,'.
                        ' I hope to be back for a longer visit in the future.',
            'expectedResult' => [
                'staff' => [
                    'score' => 200,
                    'criteria' => ['friendly', 'helpful']
                ]
            ]
        ]];
    }
}
