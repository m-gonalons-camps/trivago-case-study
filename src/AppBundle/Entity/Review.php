<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Review
 *
 * @ORM\Table(name="reviews")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReviewRepository")
 */
class Review
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
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var int
     *
     * @ORM\Column(name="total_score", type="integer", nullable=true)
     */
    private $totalScore;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    public function getId() : int {
        return $this->id;
    }

    public function setText(string $text) : Review {
        $this->text = $text;
        return $this;
    }

    public function getText() : string {
        return $this->text;
    }

    public function setTotalScore(int $totalScore) : Review {
        $this->totalScore = $totalScore;
        return $this;
    }

    public function getTotalScore() : int {
        return $this->totalScore;
    }

    public function setCreatedAt(\Datetime $createdAt) : Review {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt() : \Datetime {
        return $this->createdAt;
    }
}
