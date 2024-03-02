<?php

namespace App\Enums;

class StaffEnum
{
    const LOG_COLUMNS = [        
        'full_name' => 'Full Name',
        'phone' => 'Phone',
        'address' => 'Address',
        'dob' => 'Date of Birth',
        'type' => 'Employment Type',
        'salary_configs' => 'Salary Configurations',
        'position' => 'Position',
        'status' => 'Status',
        'note' => 'Note',
    ];

    const SALARY_COLUMNS = [
        'base_salary' => 'Base Salary',
        'commission' => [
            'commission_amount' => 'Commission Amount',
            'commission_unit' => 'Commission Unit',
            'commission_type' => 'Commission Type',
        ],
    ];

    /** Staff Status */
    const STATUS_CURRENT = 1;
    const STATUS_TEMPORARY_OFF = 2;
    const STATUS_OFF = 3;

    const STAFF_STATUSES = [
        self::STATUS_CURRENT,
        self::STATUS_TEMPORARY_OFF,
        self::STATUS_OFF,
    ];

    const MAP_STATUSES_COLOR = [
        self::STATUS_CURRENT => 'green',
        self::STATUS_TEMPORARY_OFF => 'orange',
        self::STATUS_OFF => 'red',
    ];

    const MAP_STATUSES = [
        self::STATUS_CURRENT => 'Current',
        self::STATUS_TEMPORARY_OFF => 'Temporary Off',
        self::STATUS_OFF => 'Off',
    ];

    /** Staff Positions */
    const POSITION_DIRECTOR = 1;
    const POSITION_ACCOUNTANT = 2;
    const POSITION_SALES = 3;
    const POSITION_CUSTOMER_SERVICE = 4;
    const POSITION_IT = 5;

    const STAFF_POSITIONS = [
        self::POSITION_DIRECTOR,
        self::POSITION_ACCOUNTANT,
        self::POSITION_SALES,
        self::POSITION_CUSTOMER_SERVICE,
        self::POSITION_IT,
    ];

    const MAP_POSITIONS = [
        self::POSITION_DIRECTOR => 'Executive',
        self::POSITION_ACCOUNTANT => 'Accountant',
        self::POSITION_SALES => 'Sales',
        self::POSITION_CUSTOMER_SERVICE => 'Customer Service',
        self::POSITION_IT => 'IT',
    ];

    /** Employment Types */
    const TYPE_FULLTIME = 1;
    const TYPE_PARTIME = 2;
    const TYPE_CASUAL = 3;
    const TYPE_INTERN = 4;

    const MAP_TYPES = [
        self::TYPE_FULLTIME => "Full Time",
        self::TYPE_PARTIME => "Part Time",
        self::TYPE_CASUAL => "Casual",
        self::TYPE_INTERN => "Intern",
    ];

    const STAFF_TYPES = [
        self::TYPE_FULLTIME,
        self::TYPE_PARTIME,
        self::TYPE_CASUAL,
        self::TYPE_INTERN,
    ];

    /** Commission Units */
    const COMMISSION_UNIT_PERCENTAGE = 1;
    const COMMISSION_UNIT_VND = 2;
    const COMMISSION_UNIT_AUD = 3;
    const COMMISSION_UNIT_USD = 4;

    const MAP_COMMISSION_UNITS = [
        self::COMMISSION_UNIT_PERCENTAGE => '%',
        self::COMMISSION_UNIT_VND => 'VND',   
        self::COMMISSION_UNIT_AUD => 'AUD',       
        self::COMMISSION_UNIT_USD => 'USD',
    ];

    const COMMISSION_UNITS = [
        self::COMMISSION_UNIT_PERCENTAGE,
        self::COMMISSION_UNIT_VND,
        self::COMMISSION_UNIT_AUD,
        self::COMMISSION_UNIT_USD,
    ];

    /* Commission Types */
    const COMMISSION_TYPE_SALE = 1;
    const COMMISSION_TYPE_ORDER = 2;

    CONST MAP_COMMISSION_TYPES = [
        self::COMMISSION_TYPE_SALE => "Every Sale",
        self::COMMISSION_TYPE_ORDER => "Every Order",
    ];

    const COMMISSION_TYPES = [
        self::COMMISSION_TYPE_SALE,
        self::COMMISSION_TYPE_ORDER,
    ];
}