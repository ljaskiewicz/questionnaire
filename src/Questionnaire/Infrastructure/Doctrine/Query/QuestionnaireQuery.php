<?php

declare(strict_types=1);

namespace Questionnaire\Infrastructure\Doctrine\Query;

use Doctrine\DBAL\Connection;
use Questionnaire\Application\Exception\NotFoundException;
use Questionnaire\Application\Query\QuestionnaireQueryInterface;
use Questionnaire\Application\Query\ViewModel\Answer;
use Questionnaire\Application\Query\ViewModel\Question;
use Questionnaire\Application\Query\ViewModel\Questionnaire;
use Questionnaire\Application\Query\ViewModel\UserQuestionnaire;
use Ramsey\Uuid\UuidInterface;

readonly class QuestionnaireQuery implements QuestionnaireQueryInterface
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    public function get(UuidInterface $id): Questionnaire
    {
        /**
         * @var array<array{'id': string,'parent_id': string|null,'question_value': string,'priority': int,'answer_value': string,'answer_id': string,'subquestion_id': string|null}> $result
         */
        $result = $this->connection->fetchAllAssociative("
            SELECT 
                q.id,
                q.parent_id,
                q.value as question_value,
                q.priority,
                a.value as answer_value,
                a.id as answer_id,
                ac.question_id as subquestion_id
            FROM question q
            JOIN answer a ON a.question_id = q.id
            LEFT JOIN action ac on a.id = ac.answer_id AND ac.type = 'ASK_SUBQUESTION'
            WHERE q.questionnaire_id = :questionnaireId
            ORDER BY parent_id DESC, q.priority
            ",
            [
                'questionnaireId' => $id,
            ]
        );

        if (!$result) {
            throw new NotFoundException('Questionnaire not found!');
        }

        $answers = [];
        $questions = [];

        foreach ($result as $question) {
            $answers[$question['id']][] = new Answer(
                $question['answer_id'],
                $question['answer_value'],
                $question['subquestion_id']
            );
        }

        foreach ($result as $question) {
            if (\array_key_exists($question['id'], $questions)) {
                continue;
            }

            $questions[$question['id']] = new Question(
                $question['id'],
                $question['question_value'],
                $answers[$question['id']],
                null !== $question['parent_id']
            );
        }

        return new Questionnaire($id->toString(), \array_values($questions));
    }

    public function getUserQuestionnaire(UuidInterface $questionnaireId, UuidInterface $userId): UserQuestionnaire
    {
        /** @var array{'id': string, 'recommended_products': string}|false $result */
        $result = $this->connection->fetchAssociative('
            SELECT 
                id,
                recommended_products
            FROM user_questionnaire
            WHERE questionnaire_id = :questionnaireId
            AND user_id = :userId
            LIMIT 1
            ',
            [
                'questionnaireId' => $questionnaireId,
                'userId' => $userId,
            ]
        );

        if (!$result) {
            throw new NotFoundException('User Questionnaire not found!');
        }

        /** @var string[] $decodedProducts */
        $decodedProducts = \json_decode($result['recommended_products']);

        return new UserQuestionnaire($result['id'], $decodedProducts);
    }
}
