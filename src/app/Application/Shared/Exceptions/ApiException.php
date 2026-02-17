<?php

declare(strict_types=1);

namespace App\Application\Shared\Exceptions;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

abstract class ApiException extends \Exception
{
    public function __construct(
        ?string $message = null,
        ?int $code = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: $message ?? $this->getTranslatedMessage(),
            code: $code ?? $this->getCode(),
            previous: $previous
        );
    }

    public function render(): JsonResponse
    {
        return ApiResponse::error(
            $this->getMessage(),
            $this->getCode(),
            $this->getData()
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getData(): ?array
    {
        return null;
    }

    protected function getTranslatedMessage(): string
    {
        $class = static::class;
        $message = __("exceptions.$class");

        if ($message === "exceptions.$class") {
            $message = $this->generateDefaultMessage();
        }

        return $message;
    }

    protected function generateDefaultMessage(): string
    {
        $className = class_basename($this);

        $message = preg_replace('/(?<! )(?<![A-Z])(?=[A-Z])/', ' ', $className);
        $message = str_replace('Exception', '', $message);
        return trim($message);
    }
}
