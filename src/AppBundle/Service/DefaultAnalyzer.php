<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class DefaultAnalyzer implements IAnalyzer {

    private $TypoFixer;
    private $DoctrineManager;

    private $lastKnownTopic;
    private $negators = ['not', 'isn\'t', 'aren\'t', 'wasn\'t', 'weren\'t', 'doesn\'t', 'didn\'t', 'won\'t', 'wouldn\'t', 'shouldn\'t', 'don\'t'];

    public function __construct(ITypoFixer $tf, EntityManagerInterface $em) {
        $this->TypoFixer = $tf;
        $this->DoctrineManager = $em;

        $this->setCriteria()->setTopics();
    }

    public function analyze(string $review) : AnalyzerResponse {
        $this->TypoFixer->fix($review);

        $divisions = $this->getReviewSentencesDivisions($review);
        $this->lastKnownTopic = "unknown";
        $AnalyzerResponse = new AnalyzerResponse();

        foreach ($divisions as $division) {
            $topic = $this->getDivisionTopic($division);
            $AnalyzerResponse->addTopic($topic);

            $divisionScore = $this->getDivisionScore($division);
            foreach ($divisionScore as $criteriaKeyword => $score) {
                $AnalyzerResponse->addCriteria($topic, $criteriaKeyword);
                $AnalyzerResponse->sumScore($topic, $score);
            }

            if ($topic !== 'unknown' && $this->lastKnownTopic === 'unknown' && in_array("unknown", $AnalyzerResponse->getTopics())) {
                $criteria = $AnalyzerResponse->getCriteria('unknown');
                $score = $AnalyzerResponse->getScore('unknown');

                $AnalyzerResponse->removeTopic('unknown');

                foreach ($criteria as $keyword) {
                    $AnalyzerResponse->addCriteria($topic, $keyword);
                }
                $AnalyzerResponse->sumScore($topic, $score);
            }

            $this->lastKnownTopic = $topic;
        }

        return $AnalyzerResponse;
    }

    private function getReviewSentencesDivisions(string $review) : array {
        $sentences = explode('.', $review);
        $divisionChars = ['!', '?', ',', ' and ', '&', ' but '];
        foreach ($divisionChars as $separator) {
            $sentenceDivisions = [];
            foreach ($sentences as $sentence) {
                $sentenceDivisions = array_merge($sentenceDivisions, explode($separator, $sentence));
            }
            $sentences = $sentenceDivisions;
        }
        return $sentences;
    }

    private function getDivisionTopic(string $division) : string {
        foreach ($this->topics as $topicEntity) {
            if ($this->topicExistsInDivision($topicEntity->getName(), $division)) 
                return $topicEntity->getName();

            $aliases = $topicEntity->getAliases();
            foreach ($aliases as $topicAliasEntity) {
                if ($this->topicExistsInDivision($topicAliasEntity->getAlias(), $division))
                    return $topicEntity->getName();
            }
        }

        return $this->lastKnownTopic;
    }

    private function topicExistsInDivision(string $topic, string $division) : bool {
        return (
            preg_match('/\\b'.$topic.'\\b/i', $division)
                ||
            preg_match('/\\b'.$this->pluralize($topic).'\\b/i', $division)
        );
    }

    private function getDivisionScore(string $division) : array {
        $sentenceScore = [];

        foreach ($this->criteria as $criteriaEntity) {
            $keyword = $criteriaEntity->getKeyword();
            $score = $criteriaEntity->getScore();

            if ($this->criteriaExistsInDivision($keyword, $division)) {
                $negatorInCriteria = false;
                foreach ($this->negators as $negator) {
                    if (stripos($keyword, $negator) !== FALSE) {
                        $negatorInCriteria = TRUE;
                        break;
                    }
                }

                if (! $negatorInCriteria) {
                    if ($this->isCriteriaNegated($keyword, $division)) {
                        if ($score > 0) {
                            $score = -$score;
                        } else { 
                            $score = 0;
                        }

                        if (!isset($sentenceScore["not " . $keyword])) $sentenceScore["not ".$keyword] = 0;
                        
                        $sentenceScore["not ".$keyword] += $score;
                        break;
                    } else {
                        if (!isset($sentenceScore[$keyword])) $sentenceScore[$keyword] = 0;

                        $sentenceScore[$keyword] += $score;
                    }
                } else {
                    if (!isset($sentenceScore[$keyword])) $sentenceScore[$keyword] = 0;
                    $sentenceScore[$keyword] += $score;
                }
            }
        }

        return $sentenceScore;
    }


    private function criteriaExistsInDivision(string $keyword, string $division) : bool {
        if (count(str_word_count($keyword, 1)) > 1) {
            return stripos($division, $keyword) !== FALSE;
        } else {
            return preg_match('/\\b'.$keyword.'\\b/', $division);
        }
    }

    private function isCriteriaNegated(string $keyword, string $division) : bool {
        $divisionWords = str_word_count($division, 1);
        foreach ($this->negators as $negator) {
            if (! preg_match('/\\b'.$negator.'\\b/i', $division)) continue;

            $pos = array_search($negator, $divisionWords);
            if ($pos === FALSE) continue;

            if (strtolower($divisionWords[$pos+1]) === 'only') continue;

            return TRUE;
        }

        return FALSE;
    }

    private function pluralize(string $singularWord) : string {
        // TODO: PLURALIZATION SERVICE
        return $singularWord;
    }

    private function setCriteria() : DefaultAnalyzer {
        $this->criteria = $this->DoctrineManager->getRepository('AppBundle:Criteria')->findAll();
        return $this;
    }

    private function setTopics() : DefaultAnalyzer {
        $this->topics = $this->DoctrineManager->getRepository('AppBundle:Topic')->findAll();
        return $this;
    }

}