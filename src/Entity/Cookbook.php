<?php

namespace App\Entity;

use App\Repository\CookbookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CookbookRepository::class)]
class Cookbook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(length: 255)]
    private ?string $cuisine = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\ManyToOne(inversedBy: 'cookbooks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bookshelf $shelf = null;

    #[ORM\ManyToMany(targetEntity: CuisineType::class, inversedBy: 'cookbooks')]
    private $cuisinetype;

    #[ORM\ManyToMany(targetEntity: Kitchen::class, mappedBy: 'book')]
    private $kitchens;

    public function __construct()
    {
        $this->cuisinetype = new ArrayCollection();
        $this->kitchens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCuisine(): ?string
    {
        return $this->cuisine;
    }

    public function setCuisine(string $cuisine): self
    {
        $this->cuisine = $cuisine;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getShelf(): ?Bookshelf
    {
        return $this->shelf;
    }

    public function setShelf(?Bookshelf $shelf): self
    {
        $this->shelf = $shelf;

        return $this;
    }

    public function __toString() {
        return $this->title . " (Author: " . $this->author . ", Cuisine: " . $this->cuisine . ", year: " . $this->year . ")";
    }

    /**
     * @return Collection<int, CuisineType>
     */
    public function getCuisinetype(): Collection
    {
        return $this->cuisinetype;
    }

    public function addCuisinetype(CuisineType $cuisinetype): self
    {
        if (!$this->cuisinetype->contains($cuisinetype)) {
            $this->cuisinetype[] = $cuisinetype;
        }

        return $this;
    }

    public function removeCuisinetype(CuisineType $cuisinetype): self
    {
        $this->cuisinetype->removeElement($cuisinetype);

        return $this;
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
            $kitchen->addBook($this);
        }

        return $this;
    }

    public function removeKitchen(Kitchen $kitchen): self
    {
        if ($this->kitchens->removeElement($kitchen)) {
            $kitchen->removeBook($this);
        }

        return $this;
    }
}
