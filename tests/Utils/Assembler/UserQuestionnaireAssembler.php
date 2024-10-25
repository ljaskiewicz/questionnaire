<?php

declare(strict_types=1);

namespace Tests\Utils\Assembler;

use Questionnaire\Domain\Entity\Questionnaire;
use Questionnaire\Domain\Entity\UserQuestionnaire;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserQuestionnaireAssembler
{
    public const string DEFAULT_ID = 'c8667b49-015a-46ed-a08d-94e711dd548b';

    public const string DEFAULT_USER_ID = '6d36bd11-671a-42b6-ba25-8d4336f7949e';

    private UuidInterface $id;

    private UuidInterface $userId;

    private Questionnaire $questionnaire;

    /**
     * @var string[]
     */
    private array $recommendedProducts = [];

    public function __construct()
    {
        $this->id = Uuid::fromString(self::DEFAULT_ID);
        $this->userId = Uuid::fromString(self::DEFAULT_USER_ID);
    }

    public function assemble(): UserQuestionnaire
    {
        return new UserQuestionnaire(
            $this->id,
            $this->userId,
            $this->questionnaire,
            $this->recommendedProducts
        );
    }

    public function withQuestionnaire(Questionnaire $questionnaire): UserQuestionnaireAssembler
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }
}
