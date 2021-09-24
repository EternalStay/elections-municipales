<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MairieRepository")
 */
class Mairie
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
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Politicien", mappedBy="mairie")
     */
    private $politiciens_inscrits;

    public function __construct()
    {
        $this->politiciens_inscrits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function __toString() {
        return $this->ville;
    }

    /**
     * @return Collection|Mairie[]
     */
    public function getPoliticiensInscrits(): Collection
    {
        return $this->politiciens_inscrits;
    }

    public function addPoliticiensInscrit(Mairie $politiciensInscrit): self
    {
        if (!$this->politiciens_inscrits->contains($politiciensInscrit)) {
            $this->politiciens_inscrits[] = $politiciensInscrit;
            $politiciensInscrit->setMairie($this);
        }

        return $this;
    }

    public function removePoliticiensInscrit(Mairie $politiciensInscrit): self
    {
        if ($this->politiciens_inscrits->contains($politiciensInscrit)) {
            $this->politiciens_inscrits->removeElement($politiciensInscrit);
            // set the owning side to null (unless already changed)
            if ($politiciensInscrit->getMairie() === $this) {
                $politiciensInscrit->setMairie(null);
            }
        }

        return $this;
    }
}
