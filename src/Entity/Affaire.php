<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AffaireRepository")
 */
class Affaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $designation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Politicien", mappedBy="affaires_impliquees", cascade="persist")
     */
    private $politiciens_impliques;

    public function __construct()
    {
        $this->politiciens_impliques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function __toString() {
        return $this->designation;
    }

    /**
     * @return Collection|Politicien[]
     */
    public function getPoliticiensImpliques(): Collection
    {
        return $this->politiciens_impliques;
    }

    public function addPoliticiensImplique(Politicien $politiciensImplique): self
    {
        if (!$this->politiciens_impliques->contains($politiciensImplique)) {
            $this->politiciens_impliques[] = $politiciensImplique;
            $politiciensImplique->addAffairesImpliquee($this);
        }

        return $this;
    }

    public function removePoliticiensImplique(Politicien $politiciensImplique): self
    {
        if ($this->politiciens_impliques->contains($politiciensImplique)) {
            $this->politiciens_impliques->removeElement($politiciensImplique);
            $politiciensImplique->removeAffairesImpliquee($this);
        }

        return $this;
    }
}
