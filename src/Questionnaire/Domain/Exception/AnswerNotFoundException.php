<?php

declare(strict_types=1);

namespace Questionnaire\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class AnswerNotFoundException extends \Exception
{
    public function __construct(UuidInterface $questionId, UuidInterface $answerId)
    {
        parent::__construct(\sprintf('Could not find answer: %s for question: %s', $answerId, $questionId));
    }
}
