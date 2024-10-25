<?php

declare(strict_types=1);

namespace Questionnaire\Domain;

use Questionnaire\Domain\Entity\Questionnaire;
use Questionnaire\Domain\Exception\QuestionnaireNotFoundException;
use Ramsey\Uuid\UuidInterface;

interface QuestionnaireRepositoryInterface
{
    /**
     * @throws QuestionnaireNotFoundException
     */
    public function get(UuidInterface $id): Questionnaire;

    public function save(Questionnaire $questionnaire): void;
}
