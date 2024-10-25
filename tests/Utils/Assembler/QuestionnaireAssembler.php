<?php

declare(strict_types=1);

namespace Tests\Utils\Assembler;

use Questionnaire\Domain\Entity\Questionnaire;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class QuestionnaireAssembler
{
    public const string DEFAULT_ID = 'b48f3104-60fa-42f0-b116-8c1a89e6af08';

    private UuidInterface $id;

    public function __construct()
    {
        $this->id = Uuid::fromString(self::DEFAULT_ID);
    }

    public function assemble(): Questionnaire
    {
        return new Questionnaire(
            $this->id,
        );
    }
}
