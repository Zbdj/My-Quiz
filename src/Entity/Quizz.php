<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizzRepository")
 */
class Quizz
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="quiz_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="quizz", fetch="EAGER")
     */
    private $reponse_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="user_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->reponse_id = new ArrayCollection();
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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getReponseId(): Collection
    {
        return $this->reponse_id;
    }

    public function addReponseId(Question $reponseId): self
    {
        if (!$this->reponse_id->contains($reponseId)) {
            $this->reponse_id[] = $reponseId;
            $reponseId->setQuizz($this);
        }

        return $this;
    }

    public function removeReponseId(Question $reponseId): self
    {
        if ($this->reponse_id->contains($reponseId)) {
            $this->reponse_id->removeElement($reponseId);
            // set the owning side to null (unless already changed)
            if ($reponseId->getQuizz() === $this) {
                $reponseId->setQuizz(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
