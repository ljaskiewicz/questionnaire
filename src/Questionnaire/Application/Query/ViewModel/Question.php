<?php

declare(strict_types=1);

namespace Questionnaire\Application\Query\ViewModel;

readonly class Question implements \JsonSerializable
{
    /**
     * @param Answer[] $answers
     */
    public function __construct(
        private string $id,
        private string $value,
        private array $answers,
        private bool $isSubquestion,
    ) {
    }

    /**
     * @return array{
     *     'id': string,
     *     'value': string,
     *     'answers': mixed,
     *     'isSubquestion': bool
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'answers' => \array_map(fn (Answer $answer): array => $answer->jsonSerialize(), $this->answers),
            'isSubquestion' => $this->isSubquestion,
        ];
    }
}
