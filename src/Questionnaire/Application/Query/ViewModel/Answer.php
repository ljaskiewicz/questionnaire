<?php

declare(strict_types=1);

namespace Questionnaire\Application\Query\ViewModel;

readonly class Answer implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $value,
        private ?string $subquestionId,
    ) {
    }

    /**
     * @return array{
     *     'id': string,
     *     'value': string,
     *     'subquestionId': ?string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'subquestionId' => $this->subquestionId,
        ];
    }
}
