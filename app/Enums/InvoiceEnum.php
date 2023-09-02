<?php
namespace App\Enums;

class InvoiceEnum
{
    /** Invoice Statuses */
    public const STATUS_ACTIVE = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_CANCELLED = 3;
    public const STATUS_WAIVED = 4;
    public const STATUS_OVERDUE = 5;

    public const MAP_INVOICE_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_ACTIVE => 'Pending',
        self::STATUS_ACTIVE => 'Cancelled',
        self::STATUS_ACTIVE => 'Waived',
        self::STATUS_ACTIVE => 'Overdue',
    ];

    /** Invoice Payment Statuses */
    public const STATUS_PAID = 1;
    public const STATUS_UNPAID = 2;
    public const STATUS_DEPOSIT = 3;
    public const STATUS_DEFAULT = 4;

    public const MAP_PAYMENT_STATUSES = [
        self::STATUS_PAID => 'Paid',
        self::STATUS_UNPAID => 'Unpaid',
        self::STATUS_DEPOSIT => 'Deposit',
        self::STATUS_DEFAULT => 'Default',
    ];

}