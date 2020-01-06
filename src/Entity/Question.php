<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
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
    private $title; 

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $answerType;
 
    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $answers = [];
    /**
     *   JSON TYPE - INFO
     * 
     *  Maps and converts array data based
     *  on PHP's JSON encoding functions. 
     *  If you know that the data to be stored always
     *  is in a valid UTF-8 encoded JSON format string,
     *  you should consider using this type. Values retrieved
     *  from the database are always
     *  converted to PHP's array or null types
     *  using PHP's json_decode() function.
     *
     *  Some vendors have a native JSON type and
     *  Doctrine will use it if possible and otherwise
     *  silently fall back to the vendor's text type to
     *  ensure the most efficient storage requirements.
     *  If the vendor does not have a native JSON type,
     *  this type requires an SQL column comment hint
     *  so that it can be reverse engineered from the database.
     *  Doctrine cannot map back this type properly
     *  on vendors not supporting column comments
     *  and will fall back to text type instead.
     *
     *  You should never rely on the order
     *  of your JSON object keys, as some vendors like MySQL
     *  sort the keys of its native JSON type
     *  using an internal order
     *  which is also subject to change.
     * 
    */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;

        return $this;
    }

    public function getAnswerType(): ?string
    {
        return $this->answerType;
    }

    public function setAnswerType(string $answerType): self
    {
        $this->answerType = $answerType;

        return $this; 
    }

    public function getAnswers(): ?array
    {
        return $this->answers;
    }

    public function setAnswers(?array $answers): self
    {
        $this->answers = $answers;

        return $this;
    }

}
