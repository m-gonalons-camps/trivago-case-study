<?php

namespace AppBundle\Service;

final class AnalyzerResponse {

    private $finalScore;

    public function __construct() {
        $this->finalScore = [];
    }

    public function addTopic(string $topic) : void {
        if ($this->topicExists($topic)) return;

        $this->finalScore[$topic] = [
            'score' => 0,
            'criteria' => []
        ];
    }

    public function sumScore(string $topic, int $score) : int {
        if (! $this->topicExists($topic))
            throw new \Exception('Topic does not exist.');
        
        $this->finalScore[$topic]['score'] += $score;
        return $this->finalScore[$topic]['score'];
    }

    public function addCriteria(string $topic, string $keyword) : void {
        if (! $this->topicExists($topic))
            throw new \Exception('Topic does not exist.');

        $this->finalScore[$topic]['criteria'][] = $keyword;
    }

    public function getTopics() : array {
        return array_keys($this->finalScore);
    }

    public function getScore(?string $topic = NULL) : int {
        if ($topic) {
            if (! $this->topicExists($topic))
                throw new \Exception('Topic does not exist.');
            
            $score = $this->finalScore[$topic]['score'];
        } else {
            $totalScore = 0;
            foreach ($this->finalScore as $topic => $topicScore) {
                $totalScore += $topicScore['score'];
            }
            $score = $totalScore;
        }

        return $score;
    }

    public function getCriteria(?string $topic = NULL) : array {
        if ($topic) {
            if (! $this->topicExists($topic))
                throw new \Exception('Topic does not exist.');
            
            $criteria = $this->finalScore[$topic]['criteria'];
        } else {
            $criteria = [];
            foreach ($this->finalScore as $topic => $topicScore) {
                $criteria = array_merge($criteria, $topicScore['criteria']);
            }
        }

        return $criteria;
    }

    public function getFullResults() : array {
        return $this->finalScore;
    }
   

    private function topicExists(string $topic) : bool {
        return array_key_exists($topic, $this->finalScore);
    }

}