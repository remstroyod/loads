<?php

use App\Enums\OrderStatusesEnum;
use App\Enums\UserDriverDocumentTypeEnum;
use App\Enums\UserGenderEnum;
use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Enums\UserSubcontractorsEnum;
use App\Http\Resources\Api\CountryResource;
use App\Http\Resources\Api\DriverFileTypeResource;
use App\Http\Resources\Api\LanguageResource;
use App\Http\Resources\Api\MiscellaneouResource;
use App\Http\Resources\Api\OrderStatusesResource;
use App\Http\Resources\Api\PositionResource;
use App\Http\Resources\Api\TractorResource;
use App\Http\Resources\Api\TrailerResource;
use App\Http\Resources\Api\UserGenderResource;
use App\Http\Resources\Api\UserRolesResource;
use App\Http\Resources\Api\UserStatusesResource;
use App\Http\Resources\Api\UserSubcontractorResource;
use App\Models\Country;
use App\Models\Language;
use App\Models\Miscellaneou;
use App\Models\Position;
use App\Models\Tractor;
use App\Models\Trailer;
use App\Traits\EnumTrait;

if (! function_exists('isDevelopment'))
{
    function isDevelopment(): bool
    {
        // set default value be development
        return env('APP_DEVELOPMENT', 'development') == 'development' ?? true;
    }
}

if (! function_exists('isProduction'))
{
    function isProduction(): bool
    {
        // set default value be development
        return env('APP_DEVELOPMENT', 'development') == 'production' ?? true;
    }
}


if (!function_exists('configuration'))
{

    function configuration($clear = false, $locale = 'en')
    {

        $sessionId = session()->getId();
        $hash = hash('md5', $sessionId);

        // clear configuration from cache to force rebuild
        if ($clear) cache()->forget("configuration_{$hash}");

        return cache()->remember("configuration_{$hash}", 300, function() use($locale)
        {

            $helpers = new class {
                use EnumTrait;
            };

            $configurations = [
                'genders'               => UserGenderResource::collection($helpers->getTypeSelect(UserGenderEnum::cases())),
                'subcontractors'        => UserSubcontractorResource::collection($helpers->getTypeSelect(UserSubcontractorsEnum::cases())),
                'positions'             => PositionResource::collection(Position::all()),
                'trailers'              => TrailerResource::collection(Trailer::all()),
                'tractors'              => TractorResource::collection(Tractor::all()),
                'miscellaneous'         => MiscellaneouResource::collection(Miscellaneou::all()),
                'countries'             => CountryResource::collection(Country::all()),
                'driver_languages'      => LanguageResource::collection(Language::all()),
                'driver_document_type'  => DriverFileTypeResource::collection($helpers->getTypeSelect(UserDriverDocumentTypeEnum::cases())),
                'order'                 => [
                    'statuses' => OrderStatusesResource::collection($helpers->getTypeSelect(OrderStatusesEnum::cases(), $locale))
                ],
                'users'                 => [
                    'statuses' => UserStatusesResource::collection($helpers->getTypeSelect(UserStatusEnum::cases(), $locale)),
                    'roles' => UserRolesResource::collection($helpers->getTypeSelect(UserRoleEnum::cases(), $locale))
                ]
            ];

            return $configurations;

        });
    }

}
