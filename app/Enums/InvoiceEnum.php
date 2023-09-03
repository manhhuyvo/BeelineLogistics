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
        self::STATUS_PENDING => 'Pending',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_WAIVED => 'Waived',
        self::STATUS_OVERDUE => 'Overdue',
    ];

    public const MAP_INVOICE_STATUS_COLORS = [
        self::STATUS_ACTIVE => 'green',
        self::STATUS_PENDING => 'blue',
        self::STATUS_CANCELLED => 'orange',
        self::STATUS_WAIVED => 'gray',
        self::STATUS_OVERDUE => 'red',
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

    public const MAP_PAYMENT_STATUS_COLORS = [
        self::STATUS_PAID => 'green',
        self::STATUS_UNPAID => 'red',
        self::STATUS_DEPOSIT => 'yellow',
        self::STATUS_DEFAULT => 'gray',
    ];

    /** List of targets that could be created invoice for */
    public const TARGET_FULFILLMENT = 'fulfillment';
    public const TARGET_ORDER = 'order';
    public const TARGET_MANUAL = 'manual';

    public const MAP_TARGETS = [
        self::TARGET_FULFILLMENT => 'Fulfillment',
        self::TARGET_ORDER => 'Order',
        self::TARGET_MANUAL => 'Manual',
    ];

    public const AUTO_TARGETS = [
        self::TARGET_FULFILLMENT,
        self::TARGET_ORDER,
    ];

    /** Default item descriptions for each target */
    public const DESCRIPTION_FULFILLMENT = "All the costs related to fulfillment.";
    public const DESCRIPTION_ORDER = "All the costs related to order.";

    public const MAP_DESCRIPTION_TARGET = [
        self::TARGET_FULFILLMENT => self::DESCRIPTION_FULFILLMENT,
        self::TARGET_ORDER => self::DESCRIPTION_ORDER,
    ];

    /** Invoice Default Due Date */
    public const DEFAULT_DUE_DATE = 10;
}