<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 */
class Survey
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="survey", cascade="persist")
     */
    private $questions;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $format;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Branch", mappedBy="survey")
     */
    private $branches;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Admin", inversedBy="Surveys")
     */
    private $admin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Business", inversedBy="surveys")
     */
    private $business;
 
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->branches = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    } 

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setSurvey($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getSurvey() === $this) {
                $question->setSurvey(null);
            }
        }

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return Collection|Branch[]
     */
    public function getBranches(): Collection
    {
        return $this->branches;
    }

    public function addBranch(Branch $branch): self
    {
        if (!$this->branches->contains($branch)) {
            $this->branches[] = $branch;
            $branch->addSurvey($this);
        }

        return $this;
    }

    public function removeBranch(Branch $branch): self
    {
        if ($this->branches->contains($branch)) {
            $this->branches->removeElement($branch);
            $branch->removeSurvey($this);
        }

        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getBusiness(): ?Business
    {
        return $this->business;
    }

    public function setBusiness(?Business $business): self
    {
        $this->business = $business;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

}
