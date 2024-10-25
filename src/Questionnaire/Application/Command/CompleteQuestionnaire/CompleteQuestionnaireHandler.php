<?php

declare(strict_types=1);

namespace Questionnaire\Application\Command\CompleteQuestionnaire;

use Questionnaire\Domain\QuestionnaireRepositoryInterface;
use Shared\Bus\Command\CommandHandlerInterface;

readonly class CompleteQuestionnaireHandler implements CommandHandlerInterface
{
    public function __construct(
        private QuestionnaireRepositoryInterface $questionnaireRepository,
    ) {
    }

    public function __invoke(CompleteQuestionnaireCommand $completeQuestionnaireCommand): void
    {
        $questionnaire = $this->questionnaireRepository->get($completeQuestionnaireCommand->questionnaireId);
        $questionnaire->completeUserQuestionnaire(
            $completeQuestionnaireCommand->userId,
            $completeQuestionnaireCommand->userAnswers
        );

        $this->questionnaireRepository->save($questionnaire);
    }
}
