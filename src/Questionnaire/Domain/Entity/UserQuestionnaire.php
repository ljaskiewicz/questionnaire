<?php

declare(strict_types=1);

namespace Questionnaire\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

class UserQuestionnaire
{
    /**
     * @param string[] $recommendedProducts
     */
    public function __construct(
        private UuidInterface $id,
        private UuidInterface $userId,
        private Questionnaire $questionnaire,
        private array $recommendedProducts = [],
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        private \DateTimeImmutable $updatedAt = new \DateTimeImmutable(),
    ) {
    }

    public function getQuestionnaire(): Questionnaire
    {
        return $this->questionnaire;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    /**
     * @return string[]
     */
    public function getRecommendedProducts(): array
    {
        return $this->recommendedProducts;
    }
}
