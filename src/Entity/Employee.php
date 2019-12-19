<?php

namespace App\Entity;

use App\Entity\MappedSuperclassPerson;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 */
class Employee extends MappedSuperclassPerson
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Business", inversedBy="employees")
     */
    private $business;

    public function getBusiness(): ?Business
    {
        return $this->business;
    }

    public function setBusiness(?Business $business): self
    {
        $this->business = $business;

        return $this;
    }
}
