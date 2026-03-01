<?php

declare(strict_types=1);

namespace App\Http\Controllers\Catalog;

use App\Application\Catalog\GetSpecializationWithLanguages;
use App\Application\Catalog\GetSpecializations;
use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\SpecializationResource;
use App\Http\Resources\Catalog\SpecializationWithLanguagesResource;
use App\Http\Responses\ApiResponse;
use App\Infrastructure\Catalog\Mappers\SpecializationMapper;
use App\Models\Specialization;
use Illuminate\Http\JsonResponse;

final class CatalogController extends Controller
{
    public function __construct(
        private readonly SpecializationMapper $specializationMapper,
    ) {}

    public function specializations(GetSpecializations $getSpecializations): JsonResponse
    {
        $collection = $getSpecializations->run();

        return ApiResponse::success(data: SpecializationResource::collection($collection));
    }

    public function specializationLanguages(
        Specialization $specialization,
        GetSpecializationWithLanguages $getSpecializationWithLanguages
    ): JsonResponse {
        $domainSpec = $this->specializationMapper->toDomain($specialization);
        $data = $getSpecializationWithLanguages->run($domainSpec);

        return ApiResponse::success(data: SpecializationWithLanguagesResource::make($data));
    }
}
