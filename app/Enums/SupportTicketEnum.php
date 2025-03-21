<?php
namespace App\Enums;

class SupportTicketEnum
{
    const STATUS_ACTIVE = '1';
    const STATUS_SOLVED = '2';
    const STATUS_DELETED = '3';

    const MAP_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_SOLVED => 'Solved',
        self::STATUS_DELETED => 'Deleted',
    ];
    
    const MAP_STATUS_COLORS = [
        self::STATUS_ACTIVE => 'red',
        self::STATUS_SOLVED => 'green',
        self::STATUS_DELETED => 'gray',
    ];

    const VISIBLE_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_SOLVED => 'Solved',
    ];

    const TARGET_FULFILLMENT = 'fulfillment';
    const TARGET_ORDER = 'order';

    const CUSTOMER_FILTERABLE_COLUMNS = [
        'id',
        'title',
        'status',
    ];

    const STAFF_FILTERABLE_COLUMNS = [
        'id',
        'title',
        'status',
        'customer_id',
        'solved_user_id',
    ];

    const SUPPLIER_FILTERABLE_COLUMNS = [
        'id',
        'customer_id',
        'title',
        'status',
    ];
}