<?php

declare(strict_types=1);

namespace Shared\Validator;

use Shared\Validator\Exception\ValidatorException;
use Shared\Validator\ValidatorInterface as AppValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class Validator implements AppValidatorInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @throws ValidatorException
     */
    public function validate(mixed $data, mixed $constraints = null, mixed $groups = null, bool $isNested = true): void
    {
        /** @phpstan-ignore-next-line */
        $validationErrors = $this->validator->validate($data, $constraints, $groups);

        if (\count($validationErrors) > 0) {
            $errors = [];

            /** @var ConstraintViolation $error */
            foreach ($validationErrors as $error) {
                $errors[] = $this->mapError($error, $isNested);
            }

            throw new ValidatorException($errors, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return array{'property': string, 'message': string}
     */
    private function mapError(ConstraintViolation $error, bool $isNested): array
    {
        if (false === $isNested) {
            $errorPath = \explode('.', $error->getPropertyPath());

            return [
                'property' => \end($errorPath),
                'message' => (string) $error->getMessage(),
            ];
        }

        return [
            'property' => $error->getPropertyPath(),
            'message' => (string) $error->getMessage(),
        ];
    }
}
