<?php

namespace App\Entity;

use App\Repository\LangRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LangRepository::class)]
class Lang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $langName;

    #[ORM\Column(type: 'string', length: 5)]
    private $purcentLang;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangName(): ?string
    {
        return $this->langName;
    }

    public function setLangName(string $langName): self
    {
        $this->langName = $langName;

        return $this;
    }

    public function getPurcentLang(): ?string
    {
        return $this->purcentLang;
    }

    public function setPurcentLang(string $purcentLang): self
    {
        $this->purcentLang = $purcentLang;

        return $this;
    }
}
