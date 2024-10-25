<?php

declare(strict_types=1);

namespace Shared\Validator\Exception;

class ValidatorException extends \Exception
{
    private const MESSAGE = 'Validation exception';

    /**
     * @param array<array{property: string, message: string}> $errorList
     */
    public function __construct(private array $errorList, int $code)
    {
        parent::__construct(self::MESSAGE, $code);
    }

    /**
     * @return array<array{property: string, message: string}>
     */
    public function getErrorList(): array
    {
        return $this->errorList;
    }
}
