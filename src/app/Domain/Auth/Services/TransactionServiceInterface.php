<?php

declare(strict_types=1);

namespace App\Domain\Auth\Services;

interface TransactionServiceInterface
{
    /**
     * Выполняет операцию в транзакции
     *
     * @param callable $callback
     * @return mixed
     * @throws \Throwable
     */
    public function transaction(callable $callback): mixed;
}
