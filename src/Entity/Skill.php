<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $skillName;

    #[ORM\Column(type: 'string', length: 5)]
    private $purcentSkill;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkillName(): ?string
    {
        return $this->skillName;
    }

    public function setSkillName(string $skillName): self
    {
        $this->skillName = $skillName;

        return $this;
    }

    public function getPurcentSkill(): ?string
    {
        return $this->purcentSkill;
    }

    public function setPurcentSkill(string $purcentSkill): self
    {
        $this->purcentSkill = $purcentSkill;

        return $this;
    }
}
