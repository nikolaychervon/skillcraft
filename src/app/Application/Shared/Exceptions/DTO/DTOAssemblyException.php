<?php

declare(strict_types=1);

namespace App\Application\Shared\Exceptions\DTO;

class DTOAssemblyException extends \RuntimeException
{
    public static function dtoClassNotFound(string $dtoClass, \Throwable $previous): self
    {
        return new self(
            "DTO class '{$dtoClass}' cannot be instantiated.",
            previous: $previous
        );
    }

    public static function missingField(string $field, string $dtoClass): self
    {
        return new self(
            "Required field '{$field}' is missing for DTO {$dtoClass}."
        );
    }
}
