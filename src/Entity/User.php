<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(
        message: "Ce champ est requis."
    )]
    #[Assert\Length(
        min: 5,
        max: 25,
        maxMessage: "Votre nom d'utilisateur ne peut pas contenir plus de {{ limit }} caractères."
    )]
    private $username;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(
        message: "Ce champ est requis."
    )]
    #[Assert\Length(
        min: 6,
        max: 254,
        minMessage: "Votre mot de passe doit contenir au moins 6 caractères.",
        maxMessage: "Votre mot de passe ne peut pas contenir plus de {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])^",
        match: true,
        message: "Le mot de passe doit contenir au moins une minuscule, une majuscule et un chiffre."
    )]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message: "Ce champ est requis."
    )]
    #[Assert\Email(message: "Veuillez entrer une adresse email valide.")]
    #[Assert\Length(
        max: 254,
        maxMessage: "Votre mot de passe ne peut pas contenir plus de {{ limit }} caractères."
    )]
    private $email;

    #[ORM\Column(type: 'datetime')]
    private $dateBirthday;

    #[ORM\Column(type: 'datetime')]
    private $dateUpdate;

    #[ORM\Column(type: 'string', length: 5000)]
    #[Assert\NotBlank(
        message: "Ce champ est requis."
    )]
    private $content;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Image(
        maxSize: "700k",
        maxSizeMessage: "L'mage ne doit pas dépasser 700 ko"
    )]
    private $avatar;

    #[ORM\Column(type: 'string', length: 255)]
    private $CvFile;

    #[Assert\EqualTo(propertyPath: 'password', message: 'Le mot de passe n\'est pas identique.')]
    private $passwordConfirm;

    #[ORM\Column(type: 'string', length: 15)]
    #[Assert\NotBlank(
        message: "Ce champ est requis."
    )]
    private $phone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message: "Ce champ est requis."
    )]
    private $city;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $tokenPass;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getDateBirthday(): ?\DateTimeInterface
    {
        return $this->dateBirthday;
    }

    public function setDateBirthday(\DateTimeInterface $dateBirthday): self
    {
        $this->dateBirthday = $dateBirthday;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(\DateTimeInterface $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCvFile(): ?string
    {
        return $this->CvFile;
    }

    public function setCvFile(string $CvFile): self
    {
        $this->CvFile = $CvFile;

        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(?string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTokenPass(): ?string
    {
        return $this->tokenPass;
    }

    public function setTokenPass(?string $tokenPass): self
    {
        $this->tokenPass = $tokenPass;

        return $this;
    }
}
