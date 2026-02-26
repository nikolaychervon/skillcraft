<?php

declare(strict_types=1);

namespace App\Application\Shared\RequestData;

use App\Application\Shared\Exceptions\RequestData\RequestDataAssemblyException;
use Illuminate\Support\Str;

abstract readonly class BaseRequestData
{
    /**
     * Собирает DTO из массива (поддержка ключей в snake_case).
     *
     * @param array<string, mixed> $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        $class = static::class;
        try {
            $reflection = new \ReflectionClass($class);
            $constructor = $reflection->getConstructor();
            if ($constructor === null) {
                throw RequestDataAssemblyException::requestDataClassNotFound(
                    $class,
                    new \RuntimeException('RequestData must have a constructor'),
                );
            }

            $args = [];
            foreach ($constructor->getParameters() as $param) {
                $name = $param->getName();
                $key = self::findKey($data, $name);
                if ($key !== null) {
                    $args[$name] = $data[$key];
                } elseif ($param->isOptional()) {
                    $args[$name] = $param->getDefaultValue();
                } else {
                    throw RequestDataAssemblyException::missingField($name, $class);
                }
            }

            return $reflection->newInstanceArgs($args);
        } catch (\ReflectionException $e) {
            throw RequestDataAssemblyException::requestDataClassNotFound($class, $e);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function findKey(array $data, string $camelName): ?string
    {
        if (array_key_exists($camelName, $data)) {
            return $camelName;
        }

        $snake = Str::snake($camelName);
        if (array_key_exists($snake, $data)) {
            return $snake;
        }

        return null;
    }
}
