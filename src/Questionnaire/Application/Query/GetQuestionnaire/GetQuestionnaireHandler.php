<?php

declare(strict_types=1);

namespace Questionnaire\Application\Query\GetQuestionnaire;

use Questionnaire\Application\Query\QuestionnaireQueryInterface;
use Questionnaire\Application\Query\ViewModel\Questionnaire;
use Shared\Bus\Query\QueryHandlerInterface;

readonly class GetQuestionnaireHandler implements QueryHandlerInterface
{
    public function __construct(
        private QuestionnaireQueryInterface $questionnaireQuery,
    ) {
    }

    public function __invoke(GetQuestionnaireQuery $questionnaireQuery): Questionnaire
    {
        return $this->questionnaireQuery->get($questionnaireQuery->questionnaireId);
    }
}
