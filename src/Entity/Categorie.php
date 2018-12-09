<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quizz", mappedBy="categorie")
     */
    private $quiz_id;

    public function __construct()
    {
        $this->quiz_id = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Quizz[]
     */
    public function getQuizId(): Collection
    {
        return $this->quiz_id;
    }

    public function addQuizId(Quizz $quizId): self
    {
        if (!$this->quiz_id->contains($quizId)) {
            $this->quiz_id[] = $quizId;
            $quizId->setCategorie($this);
        }

        return $this;
    }

    public function removeQuizId(Quizz $quizId): self
    {
        if ($this->quiz_id->contains($quizId)) {
            $this->quiz_id->removeElement($quizId);
            // set the owning side to null (unless already changed)
            if ($quizId->getCategorie() === $this) {
                $quizId->setCategorie(null);
            }
        }

        return $this;
    }
}
