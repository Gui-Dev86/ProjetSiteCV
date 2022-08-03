<?php

namespace App\Entity;

use App\Repository\HobbiesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HobbiesRepository::class)]
class Hobbies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $hobbiesName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHobbiesName(): ?string
    {
        return $this->hobbiesName;
    }

    public function setHobbiesName(string $hobbiesName): self
    {
        $this->hobbiesName = $hobbiesName;

        return $this;
    }
}
