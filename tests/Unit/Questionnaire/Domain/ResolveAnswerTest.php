<?php

declare(strict_types=1);

namespace Tests\Unit\Questionnaire\Domain;

use PHPUnit\Framework\TestCase;
use Questionnaire\Domain\Entity\ActionType;
use Questionnaire\Domain\Exception\AnswerNotFoundException;
use Questionnaire\Domain\Exception\UserAnswerNotFoundException;
use Ramsey\Uuid\Uuid;
use Tests\Utils\Assembler\ActionAssembler;
use Tests\Utils\Assembler\AnswerAssembler;
use Tests\Utils\Assembler\QuestionAssembler;
use Tests\Utils\Assembler\QuestionnaireAssembler;

class ResolveAnswerTest extends TestCase
{
    public function testAnswerResolved(): void
    {
        $questionnaire = (new QuestionnaireAssembler())->assemble();
        $question = (new QuestionAssembler())->withQuestionnaire($questionnaire)->assemble();
        $answer = (new AnswerAssembler())
            ->withQuestion($question)
            ->assemble();

        $question->addAnswer($answer);

        $matchedAnswer = $question->resolveAnswer([
            $question->getId()->toString() => $answer->getId()->toString(),
        ]);

        $this->assertSame($answer, $matchedAnswer);
    }

    public function testAnswerWithSubquestionResolved(): void
    {
        $questionnaire = (new QuestionnaireAssembler())->assemble();
        $question = (new QuestionAssembler())->withQuestionnaire($questionnaire)->assemble();
        $subquestion = (new QuestionAssembler())
            ->withId(Uuid::fromString('2f55f586-71e3-4ed6-b7fb-f6b2271d1ebf'))
            ->withQuestionnaire($questionnaire)
            ->assemble();
        $subAnswer = (new AnswerAssembler())
            ->withId(Uuid::fromString('b4468929-97dc-4b51-b7f5-428f647aa75d'))
            ->withQuestion($subquestion)
            ->assemble();
        $answer = (new AnswerAssembler())
            ->withQuestion($question)
            ->assemble();
        $action = (new ActionAssembler())
            ->withQuestion($subquestion)
            ->withActionType(ActionType::ASK_SUBQUESTION)
            ->withAnswer($answer)
            ->assemble();

        $answer->addAction($action);
        $question->addAnswer($answer);
        $subquestion->addAnswer($subAnswer);

        $matchedAnswer = $question->resolveAnswer([
            $question->getId()->toString() => $answer->getId()->toString(),
            $subquestion->getId()->toString() => $subAnswer->getId()->toString(),
        ]);

        $this->assertSame($subAnswer, $matchedAnswer);
    }

    public function testThrowsUserAnswerNotFoundException(): void
    {
        $questionnaire = (new QuestionnaireAssembler())->assemble();
        $question = (new QuestionAssembler())->withQuestionnaire($questionnaire)->assemble();

        $this->expectException(UserAnswerNotFoundException::class);

        $question->resolveAnswer([]);
    }

    public function testThrowsAnswerNotFoundExceptionException(): void
    {
        $questionnaire = (new QuestionnaireAssembler())->assemble();
        $question = (new QuestionAssembler())->withQuestionnaire($questionnaire)->assemble();

        $this->expectException(AnswerNotFoundException::class);

        $question->resolveAnswer([
            $question->getId()->toString() => '081b690e-d5b6-45f7-90b4-43e5e9e97a73',
        ]);
    }
}
