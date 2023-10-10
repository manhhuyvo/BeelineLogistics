<?php
namespace App\Enums;

class CustomerMetaEnum
{
    const META_AVAILABLE_COUNTRY = 'available_country';
    const META_AVAILABLE_SERVICE = 'available_service';

    const ARRAY_TYPE_META = [
        self::META_AVAILABLE_COUNTRY,
        self::META_AVAILABLE_SERVICE,
    ];

    const VALID_META = [
        self::META_AVAILABLE_COUNTRY,
        self::META_AVAILABLE_SERVICE,
    ];
}