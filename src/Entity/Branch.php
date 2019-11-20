<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BranchRepository")
 */
class Branch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Survey", inversedBy="branches")
     */
    private $survey;

    public function __construct()
    {
        $this->survey = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Survey[]
     */
    public function getSurvey(): Collection
    {
        return $this->survey;
    }

    public function addSurvey(Survey $survey): self
    {
        if (!$this->survey->contains($survey)) {
            $this->survey[] = $survey;
        }

        return $this;
    }

    public function removeSurvey(Survey $survey): self
    {
        if ($this->survey->contains($survey)) {
            $this->survey->removeElement($survey);
        }

        return $this;
    }
}
