<?php

declare(strict_types=1);

namespace Questionnaire\Entrypoint\Http\Controller;

use Questionnaire\Application\Exception\NotFoundException;
use Questionnaire\Application\Query\GetUserQuestionnaire\GetUserQuestionnaireQuery;
use Ramsey\Uuid\Uuid;
use Shared\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

readonly class GetUserQuestionnaireAction
{
    public function __construct(
        private QueryBus $queryBus,
    ) {
    }

    #[Route(
        '/user/{userId}/questionnaire/{questionnaireId}',
        name: 'get_user_questionnaire',
        requirements: ['userId' => Requirement::UUID_V4, 'questionnaireId' => Requirement::UUID_V4],
        methods: [Request::METHOD_GET]
    )]
    public function __invoke(string $userId, string $questionnaireId): JsonResponse
    {
        try {
            $result = $this->queryBus->dispatch(
                new GetUserQuestionnaireQuery(
                    Uuid::fromString($userId),
                    Uuid::fromString($questionnaireId),
                )
            );

            return new JsonResponse($result, Response::HTTP_OK);
        } catch (\Throwable $exception) {
            if ($exception->getPrevious() instanceof NotFoundException) {
                throw new NotFoundHttpException();
            }

            throw $exception;
        }
    }
}
