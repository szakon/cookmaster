<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 255)]
    private ?string $bio = null;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Bookshelf::class, orphanRemoval: true)]
    private Collection $bookshelf;

    public function __construct()
    {
        $this->bookshelf = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * @return Collection<int, Bookshelf>
     */
    public function getBookshelf(): Collection
    {
        return $this->bookshelf;
    }

    public function addBookshelf(Bookshelf $bookshelf): self
    {
        if (!$this->bookshelf->contains($bookshelf)) {
            $this->bookshelf->add($bookshelf);
            $bookshelf->setMember($this);
        }

        return $this;
    }

    public function removeBookshelf(Bookshelf $bookshelf): self
    {
        if ($this->bookshelf->removeElement($bookshelf)) {
            // set the owning side to null (unless already changed)
            if ($bookshelf->getMember() === $this) {
                $bookshelf->setMember(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->title . " (Author: " . $this->author . ", Cuisine: " . $this->cuisine . ", year: " . $this->year . ")";
    }
}
