<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $titleProject;

    #[ORM\Column(type: 'string', length: 5000)]
    private $contentProject;

    #[ORM\Column(type: 'datetime')]
    private $dateCreateProject;

    #[ORM\Column(type: 'datetime')]
    private $dateUpdateProject;

    #[ORM\Column(type: 'boolean')]
    private $isActive;

    #[ORM\Column(type: 'string', length: 255)]
    private $imageProject;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleProject(): ?string
    {
        return $this->titleProject;
    }

    public function setTitleProject(string $titleProject): self
    {
        $this->titleProject = $titleProject;

        return $this;
    }

    public function getContentProject(): ?string
    {
        return $this->contentProject;
    }

    public function setContentProject(string $contentProject): self
    {
        $this->contentProject = $contentProject;

        return $this;
    }

    public function getDateCreateProject(): ?\DateTimeInterface
    {
        return $this->dateCreateProject;
    }

    public function setDateCreateProject(\DateTimeInterface $dateCreateProject): self
    {
        $this->dateCreateProject = $dateCreateProject;

        return $this;
    }

    public function getDateUpdateProject(): ?\DateTimeInterface
    {
        return $this->dateUpdateProject;
    }

    public function setDateUpdateProject(\DateTimeInterface $dateUpdateProject): self
    {
        $this->dateUpdateProject = $dateUpdateProject;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getImageProject(): ?string
    {
        return $this->imageProject;
    }

    public function setImageProject(?string $imageProject): self
    {
        $this->imageProject = $imageProject;

        return $this;
    }
}
