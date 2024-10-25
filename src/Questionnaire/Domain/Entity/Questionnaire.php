<?php

declare(strict_types=1);

namespace Questionnaire\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Questionnaire\Domain\Exception\UserQuestionnaireAlreadyExistsException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Questionnaire
{
    /**
     * @var Collection<int, Question>
     */
    private Collection $questions;

    /**
     * @var Collection<int, UserQuestionnaire>
     */
    private Collection $userQuestionnaires;

    public function __construct(
        private UuidInterface $id,
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        private \DateTimeImmutable $updatedAt = new \DateTimeImmutable(),
    ) {
        $this->questions = new ArrayCollection();
        $this->userQuestionnaires = new ArrayCollection();
    }

    public function addQuestion(Question $question): void
    {
        $this->questions->add($question);
    }

    public function addUserQuestionnaire(UserQuestionnaire $userQuestionnaire): void
    {
        $this->userQuestionnaires->add($userQuestionnaire);
    }

    /**
     * @param array<string, string> $userAnswers
     */
    public function completeUserQuestionnaire(UuidInterface $userId, array $userAnswers): void
    {
        $this->assertUserQuestionnaireAlreadyExists($userId);

        $includedProducts = [];
        $excludedProducts = [];

        /** @var Question $question */
        foreach ($this->questions as $question) {
            if ($question->isChild()) {
                continue;
            }

            $answer = $question->resolveAnswer($userAnswers);
            foreach ($answer->getActions() as $action) {
                if ($action->isIncludeProductsAction()) {
                    $includedProducts = array_merge($includedProducts, $action->getProducts());
                } elseif ($action->isExcludeProductsAction()) {
                    $excludedProducts = array_merge($excludedProducts, $action->getProducts());
                }
            }
        }

        $includedProducts = \array_unique($includedProducts);
        $excludedProducts = \array_unique($excludedProducts);

        $this->userQuestionnaires->add(
            new UserQuestionnaire(
                Uuid::uuid4(),
                $userId,
                $this,
                \array_values(\array_diff($includedProducts, $excludedProducts)),
            )
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    private function assertUserQuestionnaireAlreadyExists(UuidInterface $userId): void
    {
        $matchedUserQuestionnaire = $this->userQuestionnaires->filter(
            function (UserQuestionnaire $userQuestionnaire) use ($userId) {
                return $userQuestionnaire->getQuestionnaire()->getId()->equals($this->getId()) && $userQuestionnaire->getUserId()->equals($userId);
            })->first();

        if ($matchedUserQuestionnaire) {
            throw new UserQuestionnaireAlreadyExistsException($this->getId(), $userId);
        }
    }

    /**
     * @return UserQuestionnaire[]
     */
    public function getUserQuestionnaires(): array
    {
        return $this->userQuestionnaires->toArray();
    }
}
