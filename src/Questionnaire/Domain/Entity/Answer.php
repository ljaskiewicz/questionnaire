<?php

declare(strict_types=1);

namespace Questionnaire\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Questionnaire\Domain\Exception\SubquestionNotFoundException;
use Ramsey\Uuid\UuidInterface;

class Answer
{
    /**
     * @var Collection<int, Action>
     */
    private Collection $actions;

    public function __construct(
        private UuidInterface $id,
        private Question $question,
        private string $value,
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        private \DateTimeImmutable $updatedAt = new \DateTimeImmutable(),
    ) {
        $this->actions = new ArrayCollection();
    }

    public function addAction(Action $action): void
    {
        $this->actions->add($action);
    }

    public function hasSubquestionAction(): bool
    {
        return (bool) $this->actions->filter(function (Action $action) {
            return $action->isSubquestionAction();
        })->first();
    }

    public function getSubquestion(): Question
    {
        $subquestionAction = $this->actions->filter(function (Action $action) {
            return $action->isSubquestionAction();
        })->first();

        if ($subquestionAction && null !== $subquestionAction->getQuestion()) {
            return $subquestionAction->getQuestion();
        }

        throw new SubquestionNotFoundException($this->id);
    }

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions->toArray();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
