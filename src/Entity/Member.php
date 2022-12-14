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

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Bookshelf::class, orphanRemoval: true, cascade: ["persist"])]
    private Collection $bookshelf;

    #[ORM\OneToMany(mappedBy: 'Owner', targetEntity: Kitchen::class)]
    private $kitchens;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Cookbook::class, orphanRemoval: true)]
    private $cookbooks;

    public function __construct()
    {
        $this->bookshelf = new ArrayCollection();
        $this->kitchens = new ArrayCollection();
        $this->cookbooks = new ArrayCollection();
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
        return $this->Name;
    }

    /**
     * @return Collection<int, Kitchen>
     */
    public function getKitchens(): Collection
    {
        return $this->kitchens;
    }

    public function addKitchen(Kitchen $kitchen): self
    {
        if (!$this->kitchens->contains($kitchen)) {
            $this->kitchens[] = $kitchen;
            $kitchen->setOwner($this);
        }

        return $this;
    }

    public function removeKitchen(Kitchen $kitchen): self
    {
        if ($this->kitchens->removeElement($kitchen)) {
            // set the owning side to null (unless already changed)
            if ($kitchen->getOwner() === $this) {
                $kitchen->setOwner(null);
            }
        }

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
            $this->cookbooks[] = $cookbook;
            $cookbook->setMember($this);
        }

        return $this;
    }

    public function removeCookbook(Cookbook $cookbook): self
    {
        if ($this->cookbooks->removeElement($cookbook)) {
            // set the owning side to null (unless already changed)
            if ($cookbook->getMember() === $this) {
                $cookbook->setMember(null);
            }
        }

        return $this;
    }
}
