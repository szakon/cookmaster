<?php

namespace App\Entity;

use App\Repository\KitchenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KitchenRepository::class)]
class Kitchen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'boolean')]
    private $published;

    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'kitchens')]
    #[ORM\JoinColumn(nullable: false)]
    private $Owner;

    #[ORM\ManyToMany(targetEntity: Cookbook::class, inversedBy: 'kitchens')]
    private $book;

    public function __construct()
    {
        $this->book = new ArrayCollection();
    }

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

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getOwner(): ?Member
    {
        return $this->Owner;
    }

    public function setOwner(?Member $Owner): self
    {
        $this->Owner = $Owner;

        return $this;
    }

    /**
     * @return Collection<int, Cookbook>
     */
    public function getBook(): Collection
    {
        return $this->book;
    }

    public function addBook(Cookbook $book): self
    {
        if (!$this->book->contains($book)) {
            $this->book[] = $book;
        }

        return $this;
    }

    public function removeBook(Cookbook $book): self
    {
        $this->book->removeElement($book);

        return $this;
    }
}
