<?php

namespace App\Entity;

use App\Repository\CuisineTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CuisineTypeRepository::class)]
class CuisineType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $label;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subCuisine')]
    private $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private $subCuisine;

    #[ORM\ManyToMany(targetEntity: Cookbook::class, mappedBy: 'cuisinetype')]
    private $cookbooks;

    public function __construct()
    {
        $this->subCuisine = new ArrayCollection();
        $this->cookbooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubCuisine(): Collection
    {
        return $this->subCuisine;
    }

    public function addSubCuisine(self $subCuisine): self
    {
        if (!$this->subCuisine->contains($subCuisine)) {
            $this->subCuisine[] = $subCuisine;
            $subCuisine->setParent($this);
        }

        return $this;
    }

    public function removeSubCuisine(self $subCuisine): self
    {
        if ($this->subCuisine->removeElement($subCuisine)) {
            // set the owning side to null (unless already changed)
            if ($subCuisine->getParent() === $this) {
                $subCuisine->setParent(null);
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
            $cookbook->addCuisinetype($this);
        }

        return $this;
    }

    public function removeCookbook(Cookbook $cookbook): self
    {
        if ($this->cookbooks->removeElement($cookbook)) {
            $cookbook->removeCuisinetype($this);
        }

        return $this;
    }

    public function __toString() {
        return $this->label;
    }

}
