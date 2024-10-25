<?php

declare(strict_types=1);

namespace Integration;

use Questionnaire\Domain\Entity\ActionType;
use Questionnaire\Domain\QuestionnaireRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\Utils\Assembler\ActionAssembler;
use Tests\Utils\Assembler\AnswerAssembler;
use Tests\Utils\Assembler\QuestionAssembler;
use Tests\Utils\Assembler\QuestionnaireAssembler;

class CompleteQuestionnaireTest extends WebTestCase
{
    private KernelBrowser $client;

    private ContainerInterface $container;

    public function testCompleteQuestionnaire(): void
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
            ->withId(Uuid::fromString('36eec52e-7a4f-4fd8-b949-2ed3ff691195'))
            ->withProducts(['tadalafil-10'])
            ->withAnswer($answer2)
            ->assemble();
        $action3 = (new ActionAssembler())
            ->withId(Uuid::fromString('77a99edc-58fa-4c92-9c7b-37b937784fa0'))
            ->withProducts(['sildafil-100'])
            ->withActionType(ActionType::EXCLUDE_PRODUCTS)
            ->withAnswer($answer2)
            ->assemble();

        $answer2->addAction($action2);
        $answer2->addAction($action3);
        $question2->addAnswer($answer2);
        $questionnaire->addQuestion($question2);

        /** @var QuestionnaireRepositoryInterface $questionnaireRepository */
        $questionnaireRepository = $this->container->get(QuestionnaireRepositoryInterface::class);
        $questionnaireRepository->save($questionnaire);

        $content = [
            'questionnaireId' => $questionnaire->getId()->toString(),
            'userId' => '143e3e7f-e866-4be2-9b5a-8cfc71cd6a0b',
            'userAnswers' => [
                [
                    'questionId' => $question1->getId()->toString(),
                    'answerId' => $answer1->getId()->toString(),
                ],
                [
                    'questionId' => $question2->getId()->toString(),
                    'answerId' => $answer2->getId()->toString(),
                ],
            ],
        ];

        $this->client->jsonRequest('POST', '/questionnaire', $content);
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $questionnaire = $questionnaireRepository->get($questionnaire->getId());
        $userQuestionnaire = $questionnaire->getUserQuestionnaires()[0];

        $this->assertEquals('143e3e7f-e866-4be2-9b5a-8cfc71cd6a0b', $userQuestionnaire->getUserId()->toString());
        $this->assertEquals($questionnaire->getId()->toString(), $userQuestionnaire->getQuestionnaire()->getId()->toString());
    }

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->container = self::getContainer();
    }
}
