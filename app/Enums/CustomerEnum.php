<?php
namespace App\Enums;

class CustomerEnum
{
    const RECEIVER_HCM_ZONE = 1;
    const RECEIVER_HANOI_ZONE = 2;
    const RECEIVER_REGIONAL_ZONE = 3;

    const MAP_ZONES = [
        self::RECEIVER_HCM_ZONE => "HCM",
        self::RECEIVER_HANOI_ZONE => "HANOI",
        self::RECEIVER_REGIONAL_ZONE => "REGIONAL",
    ];

    const RECEIVER_ZONES = [
        self::RECEIVER_HCM_ZONE,
        self::RECEIVER_HANOI_ZONE,
        self::RECEIVER_REGIONAL_ZONE,
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_CANCEL = 2;
    const STATUS_PENDING = 3;

    const TYPE_RETAILER = 1;
    const TYPE_WHOLESALER = 2;
    const TYPE_BUSINESS = 3;

    const CUSTOMER_TYPES = [
        self::TYPE_RETAILER,
        self::TYPE_WHOLESALER,
        self::TYPE_BUSINESS,
    ];

    const CUSTOMER_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_CANCEL,
        self::STATUS_PENDING,
    ];

    const MAP_STATUSES_COLOR = [
        self::STATUS_ACTIVE => 'green',
        self::STATUS_PENDING => 'yellow',
        self::STATUS_CANCEL => 'red',
    ];

    const MAP_TYPES = [
        self::TYPE_RETAILER => 'Retailer',
        self::TYPE_WHOLESALER => 'Wholesaler',
        self::TYPE_BUSINESS => 'Business',
    ];

    const MAP_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_CANCEL => 'Cancel',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PENDING => 'Pending',
    ];

    const PRICING_TYPE_FULFILMENT = 'fulfillment_pricing';
    const FULFILLMENT_PER_ORDER = 'fulfillment_per_order';
    const FULFILLMENT_PERCENTAGE = 'fulfillment_percentage';

    /** Customer Logs */
    const LOG_FILTERABLE_COLUMNS = [
        'description',
        'target_id',
        'action_by_id',
    ];

    const LOG_COLUMNS = [
        'customer_id' => 'Customer ID',
        'staff_id' => 'Staff Manage',
        'full_name' => 'Full Name',
        'phone' => 'Phone',
        'address' => 'Address',
        'price_configs' => 'Price Configurations',
        'default_sender' => 'Default Sender Details',
        'default_receiver' => 'Default Receiver Details',
        'type' => 'Type',
        'company' => 'Company',
        'status' => 'Status',
        'note' => 'Note',
    ];

    const LOG_SEPARATE_COLUMNS = [
        'price_configs',
        'default_sender',
        'default_receiver',
    ];

    const PRICE_COLUMNS = [
        self::PRICING_TYPE_FULFILMENT => [
            self::FULFILLMENT_PER_ORDER => [
                'fulfillment_per_order_amount' => 'Amount Per Fulfillment',
                'fulfillment_per_order_unit' => 'Unit Per Fulfillment',
            ],
            self::FULFILLMENT_PERCENTAGE => [
                'fulfillment_percentage_amount' => 'Percentage Amount Per Fulfillment',
                'fulfillment_percentage_unit' => 'Percentage Unit Per Fulfillment',
            ],
        ],
    ];

    const DEFAULT_SENDER_RECEIVER_COLUMNS = [
        'full_name' => 'Full Name',
        'phone' => 'Phone',
        'address' => 'Address',
    ];
}