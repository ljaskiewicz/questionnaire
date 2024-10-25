<?php

declare(strict_types=1);

namespace Questionnaire\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Questionnaire\Domain\Exception\AnswerNotFoundException;
use Questionnaire\Domain\Exception\UserAnswerNotFoundException;
use Ramsey\Uuid\Nonstandard\Uuid;
use Ramsey\Uuid\UuidInterface;

class Question
{
    /**
     * @var Collection<int, Answer>
     */
    public Collection $answers;

    /**
     * @var Collection<int, Question>
     */
    public Collection $children;

    public function __construct(
        public UuidInterface $id,
        private Questionnaire $questionnaire,
        private string $value,
        private int $priority,
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        private \DateTimeImmutable $updatedAt = new \DateTimeImmutable(),
        private ?Question $parent = null,
    ) {
        $this->children = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function addAnswer(Answer $answer): void
    {
        $this->answers->add($answer);
    }

    public function isChild(): bool
    {
        return null != $this->parent;
    }

    /**
     * @param array<string, string> $userAnswers
     */
    public function resolveAnswer(array $userAnswers): Answer
    {
        $selectedAnswer = $userAnswers[$this->id->toString()]
            ?? throw new UserAnswerNotFoundException($this->id);
        $selectedAnswer = Uuid::fromString($selectedAnswer);

        $matchedAnswer = $this->answers->filter(function (Answer $answer) use ($selectedAnswer) {
            return $answer->getId()->equals($selectedAnswer);
        })->first();

        if (!$matchedAnswer) {
            throw new AnswerNotFoundException($this->id, $selectedAnswer);
        }

        if ($matchedAnswer->hasSubquestionAction()) {
            $subquestion = $matchedAnswer->getSubquestion();

            return $subquestion->resolveAnswer($userAnswers);
        }

        return $matchedAnswer;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
