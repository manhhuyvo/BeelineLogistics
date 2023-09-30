<?php
namespace App\Enums;

class ProductPaymentEnum
{
    const PAYMENT_METHOD_TRANSFER = '1';
    const PAYMENT_METHOD_CASH = '2';
    
    const MAP_PAYMENT_METHODS = [
        self::PAYMENT_METHOD_TRANSFER => 'Bank Transfer',
        self::PAYMENT_METHOD_CASH => 'Cash',
    ];

    const STATUS_APPROVED = '1';
    const STATUS_PENDING = '2';
    const STATUS_REJECTED = '3';
    const STATUS_DELETED = '4';
    
    const MAP_STATUSES = [
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_DELETED => 'Deleted',
    ];
    
    const MAP_STATUS_COLORS = [
        self::STATUS_APPROVED => 'green',
        self::STATUS_PENDING => 'blue',
        self::STATUS_REJECTED => 'red',
        self::STATUS_DELETED => 'gray',
    ];
}