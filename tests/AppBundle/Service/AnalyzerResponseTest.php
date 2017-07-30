<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\AnalyzerResponse;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnalyzerResponseTest extends WebTestCase {

    private $AnalyzerResponse;

    public function __construct() {
        parent::__construct();
        $this->AnalyzerResponse = new AnalyzerResponse();
    }

    public function testAnalyzerResponse() {
        $this->_testAddTopic();
        $this->_testAlreadyExistingTopic();
        $this->_testSumScore();
        $this->_testInvalidSumScore();
        $this->_testGetTotalScore();
        $this->_testAddFoundCriteria();
        $this->_testInvalidAddFoundCriteria();
        $this->_testGetAllCriteria();
        $this->_testInvalidTopicForGetScore();
        $this->_testInvalidTopicForGetCriteria();
        $this->_testGetFullResults();
    }

    private function _testAddTopic() {
        $topics = ['hotel', 'bathroom'];

        foreach ($topics as $topic) {
            $this->AnalyzerResponse->addTopic($topic);
        }

        $this->assertArraySubset($topics, $this->AnalyzerResponse->getTopics());
        
        foreach ($topics as $topic) {
            $this->assertEquals(0, $this->AnalyzerResponse->getScore($topic));
        }
    }

    private function _testAlreadyExistingTopic() {
        try {
            $this->AnalyzerResponse->addTopic('bar');
            $this->AnalyzerResponse->addTopic('bar');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Topic already exists in response.');
        }
    }

    private function _testSumScore() {
        $this->AnalyzerResponse->sumScore('hotel', 3);
        $this->assertEquals(3, $this->AnalyzerResponse->getScore('hotel'));

        $this->AnalyzerResponse->sumScore('bar', -2);
        $this->assertEquals(3, $this->AnalyzerResponse->getScore('hotel'));
    }

    private function _testInvalidSumScore() {
        try {
            $this->AnalyzerResponse->sumScore('non-existant topic', 123);
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Topic does not exist.');
        }
    }

    private function _testGetTotalScore() {
        $totalScore = $this->AnalyzerResponse->getScore();
        $this->assertEquals(1, $totalScore);
    }

    private function _testAddFoundCriteria() {
        $this->AnalyzerResponse->addCriteria('hotel', 'good');
        $this->assertContains('good', $this->AnalyzerResponse->getCriteria('hotel'));

        $this->AnalyzerResponse->addCriteria('bar', 'bad');
        $this->assertContains('bad', $this->AnalyzerResponse->getCriteria('bar'));
    }

    private function _testInvalidAddFoundCriteria() {
        try {
            $this->AnalyzerResponse->addCriteria('non-existant topic', 'good');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Topic does not exist.');
        }
    }

    private function _testGetAllCriteria() {
        $criteria = $this->AnalyzerResponse->getCriteria();
        $this->assertArraySubset(['good', 'bad'], $criteria);
    }

    private function _testInvalidTopicForGetScore() {
        try {
            $this->AnalyzerResponse->getCriteria('non-existant topic');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Topic does not exist.');
        }
    }

    private function _testInvalidTopicForGetCriteria() {
        try {
            $this->AnalyzerResponse->getScore('non-existant topic');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Topic does not exist.');
        }
    }

    private function _testGetFullResults() {
        $expectedResult = [
            'hotel' => [
                'score' => 3,
                'criteria' => ['good']
            ],
            'bathroom' => [
                'score' => 0,
                'criteria' => []
            ],
            'bar' => [
                'score' => -2,
                'criteria' => ['bad']
            ]
        ];

        $result = $this->AnalyzerResponse->getFullResults();
        $this->assertEquals(json_encode($expectedResult), json_encode($result));
    }

}
