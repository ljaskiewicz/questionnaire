<?php

declare(strict_types=1);

namespace Questionnaire\Application\Query\GetUserQuestionnaire;

use Questionnaire\Application\Query\QuestionnaireQueryInterface;
use Questionnaire\Application\Query\ViewModel\UserQuestionnaire;
use Shared\Bus\Query\QueryHandlerInterface;

readonly class GetUserQuestionnaireHandler implements QueryHandlerInterface
{
    public function __construct(
        private QuestionnaireQueryInterface $questionnaireQuery,
    ) {
    }

    public function __invoke(GetUserQuestionnaireQuery $userQuestionnaireResultQuery): UserQuestionnaire
    {
        return $this->questionnaireQuery->getUserQuestionnaire(
            $userQuestionnaireResultQuery->questionnaireId,
            $userQuestionnaireResultQuery->userId,
        );
    }
}
