<?php

declare(strict_types=1);

namespace Questionnaire\Application\Command\CompleteQuestionnaire;

use Ramsey\Uuid\UuidInterface;
use Shared\Bus\Command\CommandInterface;

readonly class CompleteQuestionnaireCommand implements CommandInterface
{
    /**
     * @param array<string, string> $userAnswers
     */
    public function __construct(
        public UuidInterface $questionnaireId,
        public UuidInterface $userId,
        public array $userAnswers,
    ) {
    }
}
