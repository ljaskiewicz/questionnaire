<?php

declare(strict_types=1);

namespace Questionnaire\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

class Action
{
    /**
     * @param string[] $products
     */
    public function __construct(
        private UuidInterface $id,
        private Answer $answer,
        private ActionType $type,
        private ?Question $question = null,
        private ?array $products = null,
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        private \DateTimeImmutable $updatedAt = new \DateTimeImmutable(),
    ) {
    }

    public function isSubquestionAction(): bool
    {
        return $this->type->value === ActionType::ASK_SUBQUESTION->value;
    }

    public function isIncludeProductsAction(): bool
    {
        return $this->type->value === ActionType::INCLUDE_PRODUCTS->value;
    }

    public function isExcludeProductsAction(): bool
    {
        return $this->type->value === ActionType::EXCLUDE_PRODUCTS->value;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    /**
     * @return string[]
     */
    public function getProducts(): array
    {
        return $this->products ?? [];
    }
}
