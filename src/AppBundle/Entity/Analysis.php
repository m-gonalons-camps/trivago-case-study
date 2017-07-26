<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Analysis
 *
 * @ORM\Table(name="analysis")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnalysisRepository")
 */
class Analysis
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Entity\Review
     *
     * @ORM\ManyToOne(targetEntity="Review")
     * @ORM\JoinColumn(name="id_review", referencedColumnName="id", nullable=false)
     */
    private $review;

    /**
     * @var Entity\Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic")
     * @ORM\JoinColumn(name="id_topic", referencedColumnName="id", nullable=false)
     */
    private $topic;

    /**
     * @var Entity\AnalysisLibrary
     *
     * @ORM\ManyToOne(targetEntity="AnalysisLibrary")
     * @ORM\JoinColumn(name="id_library", referencedColumnName="id", nullable=false)
     */
    private $library;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    public function getId() : int {
        return $this->id;
    }

    public function setReview(Review $review) : Analysis {
        $this->review = $review;
        return $this;
    }

    public function getReview() : Review {
        return $this->review;
    }

    public function setTopic(Topic $topic) : Analysis {
        $this->topic = $topic;
        return $this;
    }

    public function getTopic() : Topic {
        return $this->topic;
    }

    public function setLibrary(AnalysisLibrary $library) : Analysis {
        $this->library = $library;
        return $this;
    }

    public function getLibrary() : AnalysisLibrary {
        return $this->library;
    }

    public function setScore(int $score) : Analysis {
        $this->score = $score;
        return $this;
    }

    public function getScore() : int {
        return $this->score;
    }

    public function setCreatedAt(\Datetime $createdAt) : Analysis {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt() : \Datetime {
        return $this->createdAt;
    }
}

