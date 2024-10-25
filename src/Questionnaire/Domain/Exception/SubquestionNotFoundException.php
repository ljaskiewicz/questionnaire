<?php

declare(strict_types=1);

namespace Questionnaire\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class SubquestionNotFoundException extends \Exception
{
    public function __construct(UuidInterface $answerId)
    {
        parent::__construct(\sprintf('Could not find subquestion for answer: %s', $answerId));
    }
}
