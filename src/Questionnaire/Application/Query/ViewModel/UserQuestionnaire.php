<?php

declare(strict_types=1);

namespace Questionnaire\Application\Query\ViewModel;

class UserQuestionnaire implements \JsonSerializable
{
    /**
     * @param string[] $recommendedProducts
     */
    public function __construct(
        public string $id,
        public array $recommendedProducts,
    ) {
    }

    /**
     * @return array{
     *     'id': string,
     *     'recommendedProducts': string[]
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'recommendedProducts' => $this->recommendedProducts,
        ];
    }
}
