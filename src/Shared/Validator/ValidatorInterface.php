<?php

declare(strict_types=1);

namespace Shared\Validator;

interface ValidatorInterface
{
    public function validate(mixed $data, mixed $constraints = null, mixed $groups = null, bool $isNested = true): void;
}
