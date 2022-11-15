<?php

namespace App\Entity;

use App\Repository\BookshelfRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookshelfRepository::class)]
class Bookshelf
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $shelf = null;

    #[ORM\OneToMany(mappedBy: 'shelf', targetEntity: Cookbook::class, orphanRemoval: true, cascade: ["persist"])]
    private Collection $cookbooks;

    #[ORM\ManyToOne(inversedBy: 'bookshelf')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $member = null;

    public function __construct()
    {
        $this->cookbooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShelf(): ?string
    {
        return $this->shelf;
    }

    public function setShelf(string $shelf): self
    {
        $this->shelf = $shelf;

        return $this;
    }

    /**
     * @return Collection<int, Cookbook>
     */
    public function getCookbooks(): Collection
    {
        return $this->cookbooks;
    }

    public function addCookbook(Cookbook $cookbook): self
    {
        if (!$this->cookbooks->contains($cookbook)) {
            $this->cookbooks->add($cookbook);
            $cookbook->setShelf($this);
        }

        return $this;
    }

    public function removeCookbook(Cookbook $cookbook): self
    {
        if ($this->cookbooks->removeElement($cookbook)) {
            // set the owning side to null (unless already changed)
            if ($cookbook->getShelf() === $this) {
                $cookbook->setShelf(null);
            }
        }

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function __toString() {
        return "This is the shelf: " . $this->Shelf;
    }
}
