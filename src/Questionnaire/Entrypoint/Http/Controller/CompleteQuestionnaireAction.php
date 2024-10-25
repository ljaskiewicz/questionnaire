<?php

declare(strict_types=1);

namespace Questionnaire\Entrypoint\Http\Controller;

use Questionnaire\Application\Command\CompleteQuestionnaire\CompleteQuestionnaireCommand;
use Questionnaire\Entrypoint\Http\DTO\CompleteQuestionnaireRequest;
use Ramsey\Uuid\Uuid;
use Shared\Bus\Command\CommandBusInterface;
use Shared\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

readonly class CompleteQuestionnaireAction
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('/questionnaire', name: 'complete_questionnaire', methods: [Request::METHOD_POST])]
    public function __invoke(Request $request): JsonResponse
    {
        $completeQuestionnaireRequest = $this->serializer->deserialize(
            $request->getContent(),
            CompleteQuestionnaireRequest::class,
            'json'
        );

        $this->validator->validate($completeQuestionnaireRequest);

        $this->commandBus->dispatch(
            new CompleteQuestionnaireCommand(
                Uuid::fromString($completeQuestionnaireRequest->questionnaireId),
                Uuid::fromString($completeQuestionnaireRequest->userId),
                $completeQuestionnaireRequest->answersToKeyValue()
            )
        );

        return new JsonResponse(status: Response::HTTP_CREATED);
    }
}
