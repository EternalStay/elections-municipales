<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartiRepository")
 */
class Parti
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Politicien", mappedBy="parti")
     */
    private $politiciens;

    public function __construct()
    {
        $this->politiciens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Politicien[]
     */
    public function getPoliticiens(): Collection
    {
        return $this->politiciens;
    }

    public function addPoliticien(Politicien $politicien): self
    {
        if (!$this->politiciens->contains($politicien)) {
            $this->politiciens[] = $politicien;
            $politicien->setParti($this);
        }

        return $this;
    }

    public function removePoliticien(Politicien $politicien): self
    {
        if ($this->politiciens->contains($politicien)) {
            $this->politiciens->removeElement($politicien);
            // set the owning side to null (unless already changed)
            if ($politicien->getParti() === $this) {
                $politicien->setParti(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->nom;
    }
}
