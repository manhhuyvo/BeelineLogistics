<?php

namespace App\Enums;

class userEnum
{
    /* Columns to check and add log entry if changed */
    const LOG_COLUMNS = [
        'username' => 'Username',
        'password' => 'Password',
        'level' => 'User Level',
        'status' => 'Status',
        'note' => 'Note',
    ];

    // Staff User Level
    const LEVEL_DIRECTOR = 1;
    const LEVEL_ACCOUNTANT = 2;
    const LEVEL_SALES = 3;
    const LEVEL_CUSTOMER_SERVICE = 4;
    const LEVEL_IT = 5;

    // Customer User Level
    const LEVEL_CUSTOMER = 20;
    // Supplier User Level
    const LEVEL_SUPPLIER = 30;

    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;
    const STATUS_SUSPENDED = 3;
    const STATUS_DELETE = 4;

    const TARGET_STAFF = 'staffs';
    const TARGET_CUSTOMER = 'customers';
    const TARGET_SUPPLIER = 'suppliers';

    const USER_TARGETS = [
        self::TARGET_STAFF,
        self::TARGET_CUSTOMER,
        self::TARGET_SUPPLIER,
    ];

    const USER_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_PENDING,
        self::STATUS_SUSPENDED,
        self::STATUS_DELETE,
    ];

    const USER_LEVELS = [
        self::LEVEL_DIRECTOR,
        self::LEVEL_ACCOUNTANT,
        self::LEVEL_SALES,
        self::LEVEL_CUSTOMER_SERVICE,
        self::LEVEL_IT,
        self::LEVEL_CUSTOMER,
        self::LEVEL_SUPPLIER,

    ];

    const MAP_USER_LEVELS = [
        self::LEVEL_DIRECTOR => "Executive",
        self::LEVEL_ACCOUNTANT => "Accountant",
        self::LEVEL_SALES => "Sales",
        self::LEVEL_CUSTOMER_SERVICE => "Customer Service",
        self::LEVEL_IT => "IT",
        self::LEVEL_CUSTOMER => "Customer",
        self::LEVEL_SUPPLIER => "Supplier",
    ];

    const MAP_USER_STAFF_LEVELS = [
        self::LEVEL_DIRECTOR => "Executive",
        self::LEVEL_ACCOUNTANT => "Accountant",
        self::LEVEL_SALES => "Sales",
        self::LEVEL_CUSTOMER_SERVICE => "Customer Service",
        self::LEVEL_IT => "IT",
    ];

    const MAP_TARGETS = [
        self::TARGET_STAFF => 'Staff',
        self::TARGET_CUSTOMER => 'Customer',
        self::TARGET_SUPPLIER => 'Supplier',
    ];

    const MAP_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_SUSPENDED => 'Suspended',
        self::STATUS_DELETE => 'Delete',
    ];

    const MAP_STATUSES_COLOR = [
        self::STATUS_ACTIVE => 'green',
        self::STATUS_PENDING => 'yellow',
        self::STATUS_SUSPENDED => 'red',
        self::STATUS_DELETE => 'gray',
    ];
}