<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalysisCriteria
 *
 * @ORM\Table(name="analysis_criteria")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnalysisCriteriaRepository")
 */
class AnalysisCriteria
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
     * @var Entity\Analysis
     *
     * @ORM\ManyToOne(targetEntity="Analysis")
     * @ORM\JoinColumn(name="id_analysis", referencedColumnName="id", nullable=false)
     */
    private $analysis;

    /**
     * @var Entity\Criteria
     *
     * @ORM\ManyToOne(targetEntity="Criteria")
     * @ORM\JoinColumn(name="id_criteria", referencedColumnName="id", nullable=false)
     */
    private $criteria;


    public function getId() : int {
        return $this->id;
    }

    public function setAnalysis(Analysis $analysis) : AnalysisCriteria {
        $this->analysis = $analysis;
        return $this;
    }

    public function getAnalysis() : Analysis {
        return $this->analysis;
    }

    public function setCriteria(Criteria $criteria) : AnalysisCriteria {
        $this->criteria = $criteria;
        return $this;
    }

    public function getCriteria() : Criteria {
        return $this->criteria;
    }
}

