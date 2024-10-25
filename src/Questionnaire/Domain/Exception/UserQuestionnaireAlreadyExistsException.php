<?php

declare(strict_types=1);

namespace Questionnaire\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class UserQuestionnaireAlreadyExistsException extends \Exception
{
    public function __construct(UuidInterface $questionnaireId, UuidInterface $userId)
    {
        parent::__construct(\sprintf('UserQuestionnaire already exists: userId: %s, questionnaireId: %s', $userId, $questionnaireId));
    }
}
