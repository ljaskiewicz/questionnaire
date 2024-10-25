<?php

declare(strict_types=1);

namespace Tests\Utils\Assembler;

use Questionnaire\Domain\Entity\Answer;
use Questionnaire\Domain\Entity\Question;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AnswerAssembler
{
    public const string DEFAULT_ID = 'b223755a-9ac4-4051-ac52-3c23e3c45f3e';

    private UuidInterface $id;

    private Question $question;

    private string $value = 'Default answer';

    public function __construct()
    {
        $this->id = Uuid::fromString(self::DEFAULT_ID);
    }

    public function assemble(): Answer
    {
        return new Answer(
            $this->id,
            $this->question,
            $this->value
        );
    }

    public function withQuestion(Question $question): AnswerAssembler
    {
        $this->question = $question;

        return $this;
    }

    public function withId(UuidInterface $id): AnswerAssembler
    {
        $this->id = $id;

        return $this;
    }

    public function withValue(string $value): AnswerAssembler
    {
        $this->value = $value;

        return $this;
    }
}
