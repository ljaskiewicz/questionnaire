<?php

declare(strict_types=1);

namespace Questionnaire\Entrypoint\Http\Controller;

use Questionnaire\Application\Exception\NotFoundException;
use Questionnaire\Application\Query\GetQuestionnaire\GetQuestionnaireQuery;
use Ramsey\Uuid\Uuid;
use Shared\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

readonly class GetQuestionnaireAction
{
    public function __construct(
        private QueryBus $queryBus,
    ) {
    }

    #[Route('/questionnaire/{questionnaireId}', name: 'get_questionnaire', requirements: ['id' => Requirement::UUID_V4], methods: [Request::METHOD_GET])]
    public function __invoke(string $questionnaireId): JsonResponse
    {
        try {
            $result = $this->queryBus->dispatch(
                new GetQuestionnaireQuery(
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