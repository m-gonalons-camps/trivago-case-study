<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalysisLibrary
 *
 * @ORM\Table(name="analysis_libraries")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnalysisLibraryRepository")
 */
class AnalysisLibrary
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
     * @ORM\Column(name="name", type="string", length=30, unique=true)
     */
    private $name;

    public function getId() : int {
        return $this->id;
    }

    public function setName(string $name) : AnalysisLibrary {
        $this->name = $name;
        return $this;
    }

    public function getName() : string {
        return $this->name;
    }
}

