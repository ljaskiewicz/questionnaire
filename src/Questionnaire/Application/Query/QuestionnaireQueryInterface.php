<?php

declare(strict_types=1);

namespace Questionnaire\Application\Query;

use Questionnaire\Application\Query\ViewModel\Questionnaire;
use Questionnaire\Application\Query\ViewModel\UserQuestionnaire;
use Ramsey\Uuid\UuidInterface;

interface QuestionnaireQueryInterface
{
    public function get(UuidInterface $id): Questionnaire;

    public function getUserQuestionnaire(UuidInterface $questionnaireId, UuidInterface $userId): UserQuestionnaire;
}
