<?php

declare(strict_types=1);

namespace Questionnaire\Application\Query\GetUserQuestionnaire;

use Ramsey\Uuid\UuidInterface;
use Shared\Bus\Query\QueryInterface;

readonly class GetUserQuestionnaireQuery implements QueryInterface
{
    public function __construct(
        public UuidInterface $userId,
        public UuidInterface $questionnaireId,
    ) {
    }
}
