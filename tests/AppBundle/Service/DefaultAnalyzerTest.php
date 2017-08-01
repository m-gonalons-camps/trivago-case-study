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
                        'Fortunately everything about the hotel was exceptional, and I don\'t give praise lightly. '.
                        'It was clean, stylish, roomy with excellent service in both bar where we had lunch and restaurant'.
                        ' where we had dinner. Food was beyond good and great value for money and service in both places '.
                        'Room itself was well equipped and comfortable. ',
            'expectedResult' => [
                'staff' => [
                    'score' => 300,
                    'criteria' => ['helpful', 'friendly', 'excellent']
                ],
                'hotel' => [
                    'score' => 300,
                    'criteria' => ['great', 'exceptional', 'clean']
                ],
                'food' => [
                    'score' => 200,
                    'criteria' => ['good', 'great']
                ],
                'room' => [
                    'score' => 200,
                    'criteria' => ['well', 'comfortable']
                ]
            ]
        ],[
            'review' => 'How can a place so awful be a part of such a beautiful city? '.
                        'As soon as we pulled up outside and looked at the dirty, '.
                        'holey curtains hanging like rags behind the stinking glass of the rotting windows '.
                        'we should have turned and run.'.
                        'The room was tiny and stank, as did the rest of the building.'.
                        'It was a combination of cats, mould, rot, damp, the local petting'.
                        'farm and a pair of Zoo Keepers’ wellies.'.
                        'We went out early and stayed out as late as we could manage.'.
                        'Next morning we were up and out, didn’t have breakfast as we saw the state of the'.
                        ' kitchen when we parked the car around the back in ‘Steptoes’ yard! '.
                        'Which was not only full of junk but also half the cat population of Oxford, who incidentally,'.
                        ' made themselves very at home by laying all over my car.',
            'expectedResult' => [
                'room' => [
                    'score' => -500,
                    'criteria' => ['awful', 'dirty', 'stinking', 'rotting', 'stank']
                ],
                'hotel' => [
                    'score' => -200,
                    'criteria' => ['mould', 'rot']
                ],
                'food' => [
                    'score' => -100,
                    'criteria' => ['junk']
                ]
            ]
        ],[
            'review' => 'The most disgusting and creepy hotel imaginable.'.
                        ' Only place that had vacancies. dirty sheets, porn on the TV. weird screams in the morning, '.
                        'possible blood drips on plastic mattress covering. '.
                        'This was the most frightening experience, seriously debated sleeping in Central Park instead.'.
                        ' This was worse than anything I’ve ever seen on television! Feared for my life!',
            'expectedResult' => [
                'hotel' => [
                    'score' => -100,
                    'criteria' => ['disgusting']
                ],
                'bed' => [
                    'score' => -300,
                    'criteria' => ['dirty', 'blood', 'worse']
                ],
            ]
        ],[
            'review' => 'This is not a good hotel to stay in. It is not very bad, but it is not good neither. '.
                        'The food isn\'t great and the bed wasn\'t clean. The stay wasn\'t a nightmare but it was not a good experience.',
            'expectedResult' => [
                'hotel' => [
                    'score' => -200,
                    'criteria' => ['not good', 'not bad', 'not good']
                ],
                'food' => [
                    'score' => -100,
                    'criteria' => ['isn\'t great']
                ],
                'bed' => [
                    'score' => -200,
                    'criteria' => ['wasn\'t clean', 'wasn\'t nightmare', 'not good']
                ]
            ]
        ],[
            'review' => 'This hotel is not only a very beautiful place, '.
                        'but also has the best food ever.',
            'expectedResult' => [
                'hotel' => [
                    'score' => 100,
                    'criteria' => ['beautiful'],
                ],
                'food' => [
                    'score' => 100,
                    'criteria' => ['best']
                ]
            ]
        ]];
    }
}
