<?php

declare(strict_types=1);

namespace App\Application\Shared\Exceptions\User;

use App\Application\Shared\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;

class UserNotFoundException extends ApiException
{
    protected $code = ApiResponse::HTTP_NOT_FOUND;

    /**
     * @param array<string, mixed> $searchData
     */
    public function __construct(private readonly array $searchData)
    {
        parent::__construct();
    }

    /**
     * @return array{search_data: array<string, mixed>}
     */
    public function getData(): ?array
    {
        return [
            'search_data' => $this->searchData,
        ];
    }
}
