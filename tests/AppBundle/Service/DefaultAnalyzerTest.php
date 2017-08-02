<?php

namespace Tests\AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultAnalyzerTest extends WebTestCase {

    private $DefaultAnalyzer;

    public function __construct() {
        parent::__construct();
        self::bootKernel();
        $this->DefaultAnalyzer = static::$kernel->getContainer()->get('AppBundle.DefaultAnalyzer');
    }

    public function testDefaultAnalyzer() {
        $testCases = $this->getTestCases();

        foreach ($testCases as $testCase) {
            $result = $this->DefaultAnalyzer->analyze($testCase['review'])->getFullResults();
            $this->recursiveSort($testCase['expectedResult']);
            $this->recursiveSort($result);
            $this->assertEquals($testCase['expectedResult'], $result);
        }
    }


    private function getTestCases() {
        return [[
            'review' => 'The room was great and the staff was nice. The restaurant was not bad. The room is also very spacious.',
            'expectedResult' => [
                'room' => [
                    'score' => 200,
                    'criteria' => ['spacious', 'great']
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
                    'criteria' => ['friendly', 'excellent', 'helpful']
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
                    'score' => -600,
                    'criteria' => ['awful', 'dirty', 'stinking', 'rotting', 'tiny', 'stank']
                ],
                'hotel' => [
                    'score' => -200,
                    'criteria' => ['mould', 'rot']
                ],
                'breakfast' => [
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
                    'criteria' => ['not great']
                ],
                'bed' => [
                    'score' => -200,
                    'criteria' => ['not clean', 'not nightmare', 'not good']
                ]
            ]
        ],[
            'review' => 'This hotel is not only a very enjoyable place, '.
                        'but also has the best food ever.'.
                        'The pool isn\'t especially very good',
            'expectedResult' => [
                'hotel' => [
                    'score' => 100,
                    'criteria' => ['enjoyable'],
                ],
                'food' => [
                    'score' => 100,
                    'criteria' => ['best']
                ],
                'pool' => [
                    'score' => -100,
                    'criteria' => ['not good']
                ]
            ]
        ],[
            'review' => 'The linens is good and the sheets were very clean. The managers are not helpful though. Pools aren\'t dirty.',
            'expectedResult' => [
                'bed' => [
                    'score' => 200,
                    'criteria' => ['clean', 'good']
                ],
                'staff' => [
                    'score' => -100,
                    'criteria' => ['not helpful']
                ],
                'pool' => [
                    'score' => 0,
                    'criteria' => ['not dirty']
                ]
            ]
        ]];
    }


    private function recursiveSort(&$array) : void {
        foreach ($array as &$value)
            is_array($value) && $this->recursiveSort($value);

        ksort($array) && asort($array);
        $array = array_values($array);
    }
}
