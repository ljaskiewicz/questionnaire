<?php

declare(strict_types=1);

namespace Questionnaire\Entrypoint\Http\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CompleteQuestionnaireRequest
{
    /**
     * @param array<array{'questionId': string, 'answerId': string}> $userAnswers
     */
    public function __construct(
        #[Assert\NotBlank()]
        public string $questionnaireId,
        #[Assert\NotBlank()]
        public string $userId,
        #[Assert\NotBlank()]
        public array $userAnswers = [],
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function answersToKeyValue(): array
    {
        return \array_combine(
            \array_column($this->userAnswers, 'questionId'),
            \array_column($this->userAnswers, 'answerId')
        );
    }
}
