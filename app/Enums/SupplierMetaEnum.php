<?php
namespace App\Enums;

class SupplierMetaEnum
{
    const META_AVAILABLE_COUNTRY = 'available_country';
    const META_AVAILABLE_SERVICE = 'available_service';
    const META_CUSTOMER_HANDLE = 'customer_handle';

    const ARRAY_TYPE_META = [
        self::META_AVAILABLE_COUNTRY,
        self::META_AVAILABLE_SERVICE,
        self::META_CUSTOMER_HANDLE,
    ];

    const VALID_META = [
        self::META_AVAILABLE_COUNTRY,
        self::META_AVAILABLE_SERVICE,
        self::META_CUSTOMER_HANDLE,
    ];
}