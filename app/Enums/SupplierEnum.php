<?php
namespace App\Enums;

class SupplierEnum
{
    const STATUS_CURRENT = 1;
    const STATUS_TEMPORARY_STOP = 2;
    const STATUS_STOP = 3;

    const TYPE_AUSTRALIA = 1;
    const TYPE_USA = 2;
    const TYPE_CANADA = 3;
    const TYPE_KOREA = 4;

    const SUPPLIER_TYPES = [
        self::TYPE_AUSTRALIA,
        self::TYPE_USA,
        self::TYPE_CANADA,
        self::TYPE_KOREA,
    ];

    const SUPPLIER_STATUSES = [
        self::STATUS_CURRENT,
        self::STATUS_TEMPORARY_STOP,
        self::STATUS_STOP,
    ];

    const MAP_STATUSES_COLOR = [
        self::STATUS_CURRENT => 'green',
        self::STATUS_TEMPORARY_STOP => 'orange',
        self::STATUS_STOP => 'red',
    ];

    const MAP_TYPES = [
        self::TYPE_AUSTRALIA => 'Australia',
        self::TYPE_USA => 'America',
        self::TYPE_CANADA => 'Canada',
        self::TYPE_KOREA => 'Korea',
    ];

    const MAP_STATUSES = [
        self::STATUS_CURRENT => 'Current',
        self::STATUS_TEMPORARY_STOP => 'Temporary Stop',
        self::STATUS_STOP => 'Stop',
    ];

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

    /** Supplier Logs */
    const LOG_FILTERABLE_COLUMNS = [
        'description',
        'target_id',
        'action_by_id',
    ];

    const LOG_COLUMNS = [
        'full_name' => 'Full Name',
        'phone' => 'Phone',
        'address' => 'Address',
        'type' => 'Type',
        'company' => 'Company',
        'status' => 'Status',
        'note' => 'Note',
    ];
}