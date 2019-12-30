<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection; 
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;;

/**
 * @ORM\MappedSuperclass
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\HasLifecycleCallbacks()
 */
class MappedSuperclassPerson implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $fullname;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $email;

    
    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];
    
    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;
    
    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $token;
    
    /**
     * @ORM\Column(type="integer", nullable=true, unique=true)
     */
    protected $phoneNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $picture;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * 
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    
    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    
}