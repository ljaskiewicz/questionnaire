<?php

declare(strict_types=1);

namespace Questionnaire\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class UserAnswerNotFoundException extends \Exception
{
    public function __construct(UuidInterface $questionId)
    {
        parent::__construct(\sprintf('There is no answer for question: %s', $questionId));
    }
}
