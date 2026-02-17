<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Domain\Auth\Services\TransactionServiceInterface;
use Illuminate\Support\Facades\DB;

class TransactionService implements TransactionServiceInterface
{
    public function transaction(callable $callback): mixed
    {
        return DB::transaction($callback);
    }
}
