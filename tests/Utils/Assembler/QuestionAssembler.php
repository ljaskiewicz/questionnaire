<?php

declare(strict_types=1);

namespace Tests\Utils\Assembler;

use Questionnaire\Domain\Entity\Question;
use Questionnaire\Domain\Entity\Questionnaire;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class QuestionAssembler
{
    public const string DEFAULT_ID = 'f485d399-4e08-4282-8846-ae4eca68db90';

    private UuidInterface $id;

    private Questionnaire $questionnaire;

    private string $value = 'Default question';

    private int $priority = 1;

    public function __construct()
    {
        $this->id = Uuid::fromString(self::DEFAULT_ID);
    }

    public function assemble(): Question
    {
        return new Question(
            $this->id,
            $this->questionnaire,
            $this->value,
            $this->priority
        );
    }

    public function withQuestionnaire(Questionnaire $questionnaire): QuestionAssembler
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    public function withId(UuidInterface $id): QuestionAssembler
    {
        $this->id = $id;

        return $this;
    }

    public function withValue(string $value): QuestionAssembler
    {
        $this->value = $value;

        return $this;
    }
}
