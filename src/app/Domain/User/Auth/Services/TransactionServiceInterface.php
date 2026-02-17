<?php

declare(strict_types=1);

namespace App\Domain\User\Auth\Services;

interface TransactionServiceInterface
{
    /**
     * @throws \Throwable
     */
    public function transaction(callable $callback): mixed;
}
