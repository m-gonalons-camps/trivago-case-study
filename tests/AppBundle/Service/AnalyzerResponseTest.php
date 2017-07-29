<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\AnalyzerResponse;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnalyzerResponseTest extends WebTestCase {

    private $AnalyzerResponse;

    public function __construct() {
        parent::__construct();
    }

    public function testAddTopic() {
        $topics = ['hotel', 'bathroom'];

        foreach ($topics as $topic) {
            $this->AnalyzerResponse->addTopic($topic);
        }

        $this->assertArraySubset($topics, $this->AnalyzerResponse->getTopics());
        
        foreach ($topics as $topic) {
            $this->assertEquals(0, $this->AnalyzerResponse->getScore($topic));
        }
    }

    public function testAlreadyExistingTopic() {
        try {
            $this->AnalyzerResponse->addTopic('bar');
            $this->AnalyzerResponse->addTopic('bar');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Topic already exists in response.');
        }
    }

    public function testSumScore() {
        $this->AnalyzerResponse->sumScore('hotel', 3);
        $this->assertEquals(3, $this->AnalyzerResponse->getScore('hotel'));

        $this->AnalyzerResponse->sumScore('bar', -2);
        $this->assertEquals(3, $this->AnalyzerResponse->getScore('hotel'));
    }

    public function testInvalidSumScore() {
        try {
            $this->AnalyzerResponse->sumScore('non-existant topic', 123);
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Topic does not exist.');
        }
    }

    public function testGetTotalScore() {
        $totalScore = $this->AnalyzerResponse->getScore();
        $this->assertEquals(1, $totalScore);
    }

    public function testAddFoundCriteria() {
        $this->AnalyzerResponse->addCriteria('hotel', 'good');
        $this->assertContains('good', $this->AnalyzerResponse->getCriteria('hotel'));

        $this->AnalyzerResponse->addCriteria('bar', 'bad');
        $this->assertContains('bad', $this->AnalyzerResponse->getCriteria('bar'));
    }

    public function testInvalidAddFoundCriteria() {
        try {
            $this->AnalyzerResponse->addCriteria('non-existant topic', 'good');
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), 'Topic does not exist.');
        }
    }

    public function testGetAllCriteria() {
        $criteria = $this->AnalyzerResponse->getCriteria();
        $this->assertArraySubset(['good', 'bad'], $criteria);
    }

}
