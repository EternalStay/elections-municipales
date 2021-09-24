<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PoliticienRepository")
 */
class Politicien
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
     * @ORM\Column(type="string")
     * @Assert\Regex(pattern="/^[M|F]$/", message="La valeur doit être soit F (Féminin) ou M (Masculin).")
     */
    private $sexe;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\GreaterThanOrEqual(value = 18, message="L'âge doit être supérieur à {{ compared_value }} ans pour devenir un politicien.")
     */
    private $age;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mairie", inversedBy="politiciens_inscrits")
     */
    private $mairie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Parti", inversedBy="politiciens")
     */
    private $parti;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Affaire", inversedBy="politiciens_impliques", cascade="persist")
     * @ORM\JoinTable(name="politicien_affaire")
     */
    private $affaires_impliquees;

    public function __construct()
    {
        $this->affaires_impliquees = new ArrayCollection();
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

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getMairie(): ?Mairie
    {
        return $this->mairie;
    }

    public function setMairie(?Mairie $mairie): self
    {
        $this->mairie = $mairie;

        return $this;
    }

    public function getParti(): ?Parti
    {
        return $this->parti;
    }

    public function setParti(?Parti $parti): self
    {
        $this->parti = $parti;

        return $this;
    }

    public function __toString() {
        return $this->nom;
    }

    /**
     * @return Collection|Affaire[]
     */
    public function getAffairesImpliquees(): Collection
    {
        return $this->affaires_impliquees;
    }

    public function addAffairesImpliquee(Affaire $affairesImpliquee): self
    {
        if (!$this->affaires_impliquees->contains($affairesImpliquee)) {
            $this->affaires_impliquees[] = $affairesImpliquee;
        }

        return $this;
    }

    public function removeAffairesImpliquee(Affaire $affairesImpliquee): self
    {
        if ($this->affaires_impliquees->contains($affairesImpliquee)) {
            $this->affaires_impliquees->removeElement($affairesImpliquee);
        }

        return $this;
    }
}
