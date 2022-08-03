<?php

namespace App\Entity;

use App\Repository\EnvironmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnvironmentRepository::class)]
class Environment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $environmentName;

    #[ORM\Column(type: 'string', length: 255)]
    private $imageEnvironment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnvironmentName(): ?string
    {
        return $this->environmentName;
    }

    public function setEnvironmentName(string $environmentName): self
    {
        $this->environmentName = $environmentName;

        return $this;
    }

    public function getImageEnvironment(): ?string
    {
        return $this->imageEnvironment;
    }

    public function setImageEnvironment(?string $imageEnvironment): self
    {
        $this->imageEnvironment = $imageEnvironment;

        return $this;
    }
}
