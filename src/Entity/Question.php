<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
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
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quizz", inversedBy="reponse_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quizz;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reponse", mappedBy="question")
     */
    private $question_id;

    public function __construct()
    {
        $this->question_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getQuizz(): ?Quizz
    {
        return $this->quizz;
    }

    public function setQuizz(?Quizz $quizz): self
    {
        $this->quizz = $quizz;

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getQuestionId(): Collection
    {
        return $this->question_id;
    }

    public function addQuestionId(Reponse $questionId): self
    {
        if (!$this->question_id->contains($questionId)) {
            $this->question_id[] = $questionId;
            $questionId->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionId(Reponse $questionId): self
    {
        if ($this->question_id->contains($questionId)) {
            $this->question_id->removeElement($questionId);
            // set the owning side to null (unless already changed)
            if ($questionId->getQuestion() === $this) {
                $questionId->setQuestion(null);
            }
        }

        return $this;
    }
}
