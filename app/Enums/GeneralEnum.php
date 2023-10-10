<?php
namespace App\Enums;

class GeneralEnum
{
    /** Available Export Type */
    const EXPORT_TYPE_CSV = 'csv';
    const EXPORT_TYPE_XLSX = 'xlsx';

    const MAP_EXPORT_TYPES = [
        self::EXPORT_TYPE_CSV => 'CSV',
        self::EXPORT_TYPE_XLSX => 'XLSX',
    ];

    const MAP_EXPORT_CONTENT_HEADERS = [
        self::EXPORT_TYPE_CSV => [            
            "Content-type"        => "text/csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ],
        self::EXPORT_TYPE_XLSX => [            
            "Content-type"        => "application/vnd.ms-excel",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ],
    ];

    const TARGET_FULFILLMENT = 'fulfillment';
    const TARGET_ORDER = 'order';

    const SERVICE_IMPORT = 'import';
    const SERVICE_EXPORT = 'export';
    const SERVICE_CHECKOUT = 'checkout';
    const SERVICE_FULFILLMENT = 'fulfillment';

    const MAP_SERVICES = [
        self::SERVICE_IMPORT => 'Import',
        self::SERVICE_EXPORT => 'Export',
        self::SERVICE_CHECKOUT => 'Checkout',
        self::SERVICE_FULFILLMENT => 'Fulfillment',
    ];
}