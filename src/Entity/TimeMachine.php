<?php

namespace App\Entity;

use App\Repository\TimeMachineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeMachineRepository::class)]
class TimeMachine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $resourceURL = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getResourceURL(): ?string
    {
        return $this->resourceURL;
    }

    public function setResourceURL(string $resourceURL): self
    {
        $this->resourceURL = $resourceURL;

        return $this;
    }
}
