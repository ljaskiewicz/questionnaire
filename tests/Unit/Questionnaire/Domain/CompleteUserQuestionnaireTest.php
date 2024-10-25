<?php

declare(strict_types=1);

namespace Tests\Unit\Questionnaire\Domain;

use PHPUnit\Framework\TestCase;
use Questionnaire\Domain\Entity\ActionType;
use Questionnaire\Domain\Exception\UserQuestionnaireAlreadyExistsException;
use Ramsey\Uuid\Uuid;
use Tests\Utils\Assembler\ActionAssembler;
use Tests\Utils\Assembler\AnswerAssembler;
use Tests\Utils\Assembler\QuestionAssembler;
use Tests\Utils\Assembler\QuestionnaireAssembler;
use Tests\Utils\Assembler\UserQuestionnaireAssembler;

class CompleteUserQuestionnaireTest extends TestCase
{
    public function testCompletionFailedDueToAlreadyExistingUserQuestionnaire(): void
    {
        $questionnaire = (new QuestionnaireAssembler())->assemble();
        $userQuestionnaire = (new UserQuestionnaireAssembler())->withQuestionnaire($questionnaire)->assemble();
        $questionnaire->addUserQuestionnaire($userQuestionnaire);

        $this->expectException(UserQuestionnaireAlreadyExistsException::class);

        $questionnaire->completeUserQuestionnaire(Uuid::fromString(UserQuestionnaireAssembler::DEFAULT_USER_ID), []);
    }

    public function testQuestionnaireCompleted(): void
    {
        // question 1
        $questionnaire = (new QuestionnaireAssembler())->assemble();
        $question1 = (new QuestionAssembler())
            ->withQuestionnaire($questionnaire)
            ->withValue('Question 1')
            ->assemble();

        $answer1 = (new AnswerAssembler())
            ->withValue('Question 1 answer')
            ->withQuestion($question1)
            ->assemble();
        $action1 = (new ActionAssembler())
            ->withProducts(['sildafil-50', 'sildafil-100'])
            ->withAnswer($answer1)
            ->assemble();

        $answer1->addAction($action1);
        $question1->addAnswer($answer1);
        $questionnaire->addQuestion($question1);

        // question 2
        $question2 = (new QuestionAssembler())
            ->withId(Uuid::fromString('1c5e7355-9f68-4562-8524-7b042b8df850'))
            ->withValue('Question 2')
            ->withQuestionnaire($questionnaire)
            ->assemble();

        $answer2 = (new AnswerAssembler())
            ->withId(Uuid::fromString('c30f748e-7159-4932-a2bc-6b62d4094e45'))
            ->withValue('Question 2 answer')
            ->withQuestion($question2)
            ->assemble();
        $action2 = (new ActionAssembler())
            ->withProducts(['tadalafil-10'])
            ->withAnswer($answer2)
            ->assemble();
        $action3 = (new ActionAssembler())
            ->withProducts(['sildafil-100'])
            ->withActionType(ActionType::EXCLUDE_PRODUCTS)
            ->withAnswer($answer2)
            ->assemble();

        $answer2->addAction($action2);
        $answer2->addAction($action3);
        $question2->addAnswer($answer2);
        $questionnaire->addQuestion($question2);

        $userAnswers = [
            $question1->getId()->toString() => $answer1->getId()->toString(),
            $question2->getId()->toString() => $answer2->getId()->toString(),
        ];

        $questionnaire->completeUserQuestionnaire(Uuid::fromString(UserQuestionnaireAssembler::DEFAULT_USER_ID), $userAnswers);

        $this->assertEquals(
            ['sildafil-50', 'tadalafil-10'],
            $questionnaire->getUserQuestionnaires()[0]->getRecommendedProducts()
        );
    }
}
