<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class DefaultAnalyzer implements IAnalyzer {

    private $TypoFixer;
    private $DoctrineManager;
    private $AnalyzerResponse;

    private $lastKnownTopic;

    // TODO: Think about adding the negator "never" and test it with a couple of reviews with that word.
    private $negators = ['not', 'isn\'t', 'aren\'t', 'wasn\'t', 'weren\'t', 'doesn\'t', 'didn\'t', 'won\'t', 'wouldn\'t', 'shouldn\'t', 'don\'t'];

    public function __construct(ITypoFixer $tf, EntityManagerInterface $em) {
        $this->TypoFixer = $tf;
        $this->DoctrineManager = $em;

        $this->setCriteria()->setTopics();
    }

    public function analyze(string $review) : AnalyzerResponse {
        $this->TypoFixer->fix($review);

        $divisions = $this->getSentencesDivisions($review);
        $this->lastKnownTopic = "unknown";
        $this->AnalyzerResponse = new AnalyzerResponse();

        foreach ($divisions as $division) {
            $topic = $this->getDivisionTopic($division);

            $this->AnalyzerResponse->addTopic($topic);
            $this->canReassignUnkownTopicCriteria($topic) && $this->reassignUnknownTopicCriteria($topic);
            $this->lastKnownTopic = $topic;

            $this->setTopicCriteriaAndScore($topic, $division);
        }

        return $this->AnalyzerResponse;
    }

    private function getSentencesDivisions(string $review) : array {
        $sentences = explode('.', $review);
        $divisionChars = ['!', '?', ',', ' and ', '&', ' but ', ':', ';'];
        foreach ($divisionChars as $separator) {
            $sentenceDivisions = [];
            foreach ($sentences as $sentence) {
                $sentenceDivisions = array_merge($sentenceDivisions, explode($separator, $sentence));
            }
            $sentences = $sentenceDivisions;
        }
        return $sentences;
    }

    private function reassignUnknownTopicCriteria(string $correctTopic) : void {
        $unknownTopicCriteria = $this->AnalyzerResponse->getCriteria('unknown');
        foreach ($unknownTopicCriteria as $keyword) {
            $this->AnalyzerResponse->addCriteria($correctTopic, $keyword);
        }
        $this->AnalyzerResponse->sumScore($correctTopic, $this->AnalyzerResponse->getScore('unknown'));
        $this->AnalyzerResponse->removeTopic('unknown');
    }

    private function canReassignUnkownTopicCriteria(string $possibleCorrectTopic) : bool {
        return (
            $possibleCorrectTopic !== 'unknown' &&
            $this->lastKnownTopic === 'unknown' &&
            in_array("unknown", $this->AnalyzerResponse->getTopics())
        );
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
            preg_match('/\\b'.$topic.'\\b/i', $division) ||
            preg_match('/\\b'.$this->pluralize($topic).'\\b/i', $division)
        );
    }

    private function setTopicCriteriaAndScore(string $topic, string $division) : void {
        foreach ($this->criteria as $criteriaEntity) {
            $keyword = $criteriaEntity->getKeyword();
            if (! $this->criteriaExistsInDivision($keyword, $division)) continue;

            $score = $criteriaEntity->getScore();
            if ($this->isCriteriaNegated($keyword, $division)) {
                $score = $this->adaptNegatedCriteriaScore($score);
                $keyword = "not " . $keyword;
            }
            $this->AnalyzerResponse->addCriteria($topic, $keyword);
            $this->AnalyzerResponse->sumScore($topic, $score);
        }
    }


    private function criteriaExistsInDivision(string $keyword, string $division) : bool {
        // Since a criteria keyword can have more than 1 word, for example "going to come back", 
        // we need to check that in order to use stripos for that criteria or a regexp with word boundaries (/b)
        // for single word criteria.
        if (count(str_word_count($keyword, 1)) > 1)
            return stripos($division, $keyword) !== FALSE;
        else
            return preg_match('/\\b'.$keyword.'\\b/', $division);
    }

    private function isCriteriaNegated(string $keyword, string $division) : bool {
        if ($this->criteriaKeywordHasNegators($keyword)) return FALSE;

        $divisionWords = str_word_count($division, 1);
        foreach ($this->negators as $negator) {
            $negatorIndex = array_search($negator, $divisionWords);

            if ($negatorIndex === FALSE) continue;

            // Example:
            // "Not only this is a good place, but it has nice food"
            // In this case, the "not" is not negating the "good" criteria.
            // That's why we check the word right after the negator for this particular case.
            if (strtolower($divisionWords[$negatorIndex+1]) === 'only') continue;

            return TRUE;
        }

        return FALSE;
    }

    private function adaptNegatedCriteriaScore(int $score) : int {
        // Negating a good criteria, for example "was not a good hotel", results in the score being inverted.
        // If "good" has a score of 100, negating it results in a score of -100
        // On the other hand, negating negative criteria, for example "is not a bad hotel", I think it's better
        // to treat that as neutral instead of inverting the bad criteria score.
        // I don't think that "not bad hotel" should be treated as equally as "good hotel", so I decided 
        // to treat the negated bad criteria with a neutral score of 0.
        return $score > 0 ? -$score : 0;
    }

    private function criteriaKeywordHasNegators(string $keyword) : bool {
        // Some criteria like "did not sleep" or "didn't work" have negators in them.
        // We need to check for this cases to avoid returning an score like this: "not did not sleep"
        // If the criteria has a negator in itself, we will always return that the criteria
        // is not negated, since things like "not didn't sleep" or "not not going to come back"
        // are never going to bee seen in a real hotel review.
        foreach ($this->negators as $negator)
            if (stripos($keyword, $negator) !== FALSE)
                return TRUE;
        
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