<?php

declare(strict_types=1);

namespace App\Domain\Auth\Services;

interface TokenGeneratorInterface
{
    /**
     * Генерирует случайный токен указанной длины
     *
     * @param int $length
     * @return string
     */
    public function generate(int $length): string;
}
