<?php

declare(strict_types=1);

namespace Questionnaire\Application\Query\ViewModel;

readonly class Questionnaire implements \JsonSerializable
{
    /**
     * @param Question[] $questions
     */
    public function __construct(
        private string $id,
        private array $questions,
    ) {
    }

    /**
     * @return array{
     *     'id': string,
     *     'questions': mixed
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'questions' => \array_map(fn (Question $question): array => $question->jsonSerialize(), $this->questions),
        ];
    }
}
