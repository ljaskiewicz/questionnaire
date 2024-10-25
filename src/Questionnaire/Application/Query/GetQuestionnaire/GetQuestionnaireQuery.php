<?php

declare(strict_types=1);

namespace Questionnaire\Application\Query\GetQuestionnaire;

use Ramsey\Uuid\UuidInterface;
use Shared\Bus\Query\QueryInterface;

readonly class GetQuestionnaireQuery implements QueryInterface
{
    public function __construct(
        public UuidInterface $questionnaireId,
    ) {
    }
}
