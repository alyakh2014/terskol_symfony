<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImagesRepository")
 */
class Images
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Section_id;

    /**
     * @ORM\Column(type="text")
     */
    private $Path;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSectionId(): ?int
    {
        return $this->Section_id;
    }

    public function setSectionId(?int $Section_id): self
    {
        $this->Section_id = $Section_id;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->Path;
    }

    public function setPath(string $Path): self
    {
        $this->Path = $Path;

        return $this;
    }
}
