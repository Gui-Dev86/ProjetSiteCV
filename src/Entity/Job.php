<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $titleJob;

    #[ORM\Column(type: 'string', length: 5000)]
    private $contentJob;

    #[ORM\Column(type: 'datetime')]
    private $dateBeginJob;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateFinishJob;

    #[ORM\Column(type: 'string', length: 255)]
    private $placeJob;

    #[ORM\Column(type: 'string', length: 255)]
    private $enterpriseJob;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleJob(): ?string
    {
        return $this->titleJob;
    }

    public function setTitleJob(string $titleJob): self
    {
        $this->titleJob = $titleJob;

        return $this;
    }

    public function getContentJob(): ?string
    {
        return $this->contentJob;
    }

    public function setContentJob(string $contentJob): self
    {
        $this->contentJob = $contentJob;

        return $this;
    }

    public function getDateBeginJob(): ?\DateTimeInterface
    {
        return $this->dateBeginJob;
    }

    public function setDateBeginJob(\DateTimeInterface $dateBeginJob): self
    {
        $this->dateBeginJob = $dateBeginJob;

        return $this;
    }

    public function getDateFinishJob(): ?\DateTimeInterface
    {
        return $this->dateFinishJob;
    }

    public function setDateFinishJob(?\DateTimeInterface $dateFinishJob): self
    {
        $this->dateFinishJob = $dateFinishJob;

        return $this;
    }

    public function getPlaceJob(): ?string
    {
        return $this->placeJob;
    }

    public function setPlaceJob(string $placeJob): self
    {
        $this->placeJob = $placeJob;

        return $this;
    }

    public function getEnterpriseJob(): ?string
    {
        return $this->enterpriseJob;
    }

    public function setEnterpriseJob(string $enterpriseJob): self
    {
        $this->enterpriseJob = $enterpriseJob;

        return $this;
    }
}
