<?php
namespace App\Enums;

class PaymentEnum
{
    const PAYMENT_METHOD_TRANSFER = '1';
    const PAYMENT_METHOD_CASH = '2';
    const PAYMENT_METHOD_CHEQUE = '3';
    
    const MAP_PAYMENT_METHODS = [
        self::PAYMENT_METHOD_TRANSFER => 'Bank Transfer',
        self::PAYMENT_METHOD_CASH => 'Cash',
        self::PAYMENT_METHOD_CHEQUE => 'Cheque',
    ];

    const STATUS_APPROVED = '1';
    const STATUS_PENDING = '2';
    const STATUS_DECLINED = '3';
    const STATUS_DELETED = '4';
    
    const MAP_STATUSES = [
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_DECLINED => 'Declined',
        self::STATUS_DELETED => 'Deleted',
    ];
    
    const MAP_STATUS_COLORS = [
        self::STATUS_APPROVED => 'green',
        self::STATUS_PENDING => 'blue',
        self::STATUS_DECLINED => 'red',
        self::STATUS_DELETED => 'gray',
    ];

    const FILTERABLE_COLUMNS = [
        'transaction_id',
        'user_id',
        'staff_id',
        'amount',
        'description',
        'payment_method',
        'status',
        'payment_date',
    ];
}