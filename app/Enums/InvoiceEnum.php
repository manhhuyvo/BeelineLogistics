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

    public const NO_OUTSTANDING_STATUSES = [
        self::STATUS_CANCELLED,
        self::STATUS_WAIVED,
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

    /** Available Bulk Actions */
    const BULK_MARK_ACTIVE = 'mark_active';
    const BULK_MARK_PENDING = 'mark_pending';
    const BULK_MARK_CANCELLED = 'mark_cancelled';
    const BULK_MARK_WAIVED = 'mark_waived';
    const BULK_MARK_OVERDUE = 'mark_overdue';

    const MAP_BULK_ACTIONS = [
        self::BULK_MARK_ACTIVE => 'Mark as Active',
        self::BULK_MARK_PENDING => 'Mark as Pending',
        self::BULK_MARK_CANCELLED => 'Mark as Cancelled',
        self::BULK_MARK_WAIVED => 'Mark as Waived',
        self::BULK_MARK_OVERDUE => 'Mark as Overdue',
    ];

    const MAP_BULK_AND_STATUS = [
        self::BULK_MARK_ACTIVE => self::STATUS_ACTIVE,
        self::BULK_MARK_PENDING => self::STATUS_PENDING,
        self::BULK_MARK_CANCELLED => self::STATUS_CANCELLED,
        self::BULK_MARK_WAIVED => self::STATUS_WAIVED,
        self::BULK_MARK_OVERDUE => self::STATUS_OVERDUE,
    ];

    /** Columns for export */
    const EXPORT_COLUMNS = [
        'id',
        'customer_name',
        'reference',
        'total_amount',
        'outstanding_amount',
        'unit',
        'due_date',
        'status',
        'payment_status',
        'invoice_items',
        'staff_created',
        'note',
        'date_created',
    ];
}