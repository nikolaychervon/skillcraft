<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Auth\Cache\PasswordResetTokensCacheInterface;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Auth\Services\HashServiceInterface;
use App\Domain\Auth\Services\NotificationServiceInterface;
use App\Domain\Auth\Services\TokenGeneratorInterface;
use App\Domain\Auth\Services\TokenServiceInterface;
use App\Domain\Auth\Services\TransactionServiceInterface;
use App\Infrastructure\Auth\Cache\PasswordResetTokensCache;
use App\Infrastructure\Auth\Repositories\UserRepository;
use App\Infrastructure\Auth\Services\HashService;
use App\Infrastructure\Auth\Services\NotificationService;
use App\Infrastructure\Auth\Services\TokenGenerator;
use App\Infrastructure\Auth\Services\TokenService;
use App\Infrastructure\Auth\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        // Repositories
        UserRepositoryInterface::class => UserRepository::class,

        // Cache
        PasswordResetTokensCacheInterface::class => PasswordResetTokensCache::class,

        // Services
        HashServiceInterface::class => HashService::class,
        TokenServiceInterface::class => TokenService::class,
        NotificationServiceInterface::class => NotificationService::class,
        TokenGeneratorInterface::class => TokenGenerator::class,
        TransactionServiceInterface::class => TransactionService::class,
    ];
}
