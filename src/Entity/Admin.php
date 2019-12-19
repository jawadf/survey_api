<?php

namespace App\Entity;

use App\Entity\MappedSuperclassPerson;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminRepository")
 */
class Admin extends MappedSuperclassPerson
{

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Survey", mappedBy="admin")
     */
    private $Surveys;

    public function __construct()
    {
        $this->Surveys = new ArrayCollection();
    }

    /**
     * @return Collection|Survey[]
     */
    public function getSurveys(): Collection
    {
        return $this->Surveys;
    }

    public function addSurvey(Survey $survey): self
    {
        if (!$this->Surveys->contains($survey)) {
            $this->Surveys[] = $survey;
            $survey->setAdmin($this);
        }

        return $this;
    }

    public function removeSurvey(Survey $survey): self
    {
        if ($this->Surveys->contains($survey)) {
            $this->Surveys->removeElement($survey);
            // set the owning side to null (unless already changed)
            if ($survey->getAdmin() === $this) {
                $survey->setAdmin(null);
            }
        }

        return $this;
    }
}
