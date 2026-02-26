<?php

declare(strict_types=1);

namespace App\Application\Shared\Assemblers;

use App\Application\Shared\RequestData\BaseRequestData;
use App\Application\Shared\Exceptions\RequestData\RequestDataAssemblyException;
use Illuminate\Support\Str;

/** @template RequestData of BaseRequestData */
abstract class AbstractRequestDataAssembler
{
    /**
     * Создаёт RequestData из массива данных.
     *
     * @param array<string, mixed> $data
     * @return RequestData
     */
    public function assemble(array $data): BaseRequestData
    {
        $this->validate($data);
        return $this->assembleRequestData($data);
    }

    /**
     * @param array<string, mixed> $data
     * @return BaseRequestData
     */
    protected function assembleRequestData(array $data): BaseRequestData
    {
        try {
            $requestDataClass = $this->getRequestDataClass();
            $reflection = new \ReflectionClass($requestDataClass);

            $constructor = $reflection->getConstructor();
            if ($constructor === null) {
                throw RequestDataAssemblyException::requestDataClassNotFound(
                    $requestDataClass,
                    new \RuntimeException('RequestData must have a constructor')
                );
            }

            $requestDataArgs = [];

            foreach ($constructor->getParameters() as $param) {
                $name = $param->getName();

                $candidates = [
                    $name,
                    Str::snake($name),
                ];

                $found = false;

                foreach ($candidates as $key) {
                    if (array_key_exists($key, $data)) {
                        $requestDataArgs[$name] = $data[$key];
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    if ($param->isOptional()) {
                        $requestDataArgs[$name] = $param->getDefaultValue();
                        continue;
                    }

                    throw RequestDataAssemblyException::missingField($name, $requestDataClass);
                }
            }

            /** @var BaseRequestData $requestData */
            $requestData = $reflection->newInstanceArgs($requestDataArgs);
            return $requestData;

        } catch (\ReflectionException $e) {
            throw RequestDataAssemblyException::requestDataClassNotFound(
                $this->getRequestDataClass(),
                $e
            );
        }
    }

    /**
     * @return class-string<BaseRequestData>
     */
    abstract protected function getRequestDataClass(): string;

    /**
     * @param array<string, mixed> $data
     */
    protected function validate(array $data): void
    {
    }
}
