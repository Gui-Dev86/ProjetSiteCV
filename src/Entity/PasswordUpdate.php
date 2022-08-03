<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{
    #[Assert\NotBlank(
        message: "Ce champ est requis."
    )]
    #[SecurityAssert\UserPassword(
        message: "Ce n'est pas votre mot de passe actuel."
    )]
    private $precedentPassword;
    
    #[ORM\Column(type: 'string', length: 255)]
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
    private $newPassword;

    #[Assert\EqualTo(propertyPath: 'newPassword', message: 'Le mot de passe n\'est pas identique.')]
    private $confirmPassword;

    public function getPrecedentPassword(): ?string
    {
        return $this->precedentPassword;
    }

    public function setPrecedentPassword(?string $precedentPassword): self
    {
        $this->precedentPassword = $precedentPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(?string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(?string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
