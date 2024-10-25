<?php

declare(strict_types=1);

namespace Questionnaire\Domain\Entity;

enum ActionType: string
{
    case INCLUDE_PRODUCTS = 'INCLUDE_PRODUCTS';

    case EXCLUDE_PRODUCTS = 'EXCLUDE_PRODUCTS';

    case ASK_SUBQUESTION = 'ASK_SUBQUESTION';
}
