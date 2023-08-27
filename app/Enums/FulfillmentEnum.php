<?php
namespace App\Enums;

class FulfillmentEnum
{

/** Fulfillment status Constants */
    const FULFILLMENT_STATUS_ACTIVE = 1;
    const FULFILLMENT_STATUS_CANCEL = 2;
    const FULFILLMENT_STATUS_PENDING = 3;
    const FULFILLMENT_STATUS_DELETE = 4;

    const FULFILLMENT_STATUSES = [
        self::FULFILLMENT_STATUS_ACTIVE,
        self::FULFILLMENT_STATUS_CANCEL,
        self::FULFILLMENT_STATUS_PENDING,
        self::FULFILLMENT_STATUS_DELETE,
    ];

    const MAP_FULFILLMENT_STATUSES = [
        self::FULFILLMENT_STATUS_ACTIVE => 'Active',
        self::FULFILLMENT_STATUS_CANCEL => 'Cancel',
        self::FULFILLMENT_STATUS_PENDING => 'Pending',
        self::FULFILLMENT_STATUS_DELETE => 'Delete',
    ];

    const MAP_STATUS_COLORS = [
        self::FULFILLMENT_STATUS_ACTIVE => 'green',
        self::FULFILLMENT_STATUS_CANCEL => 'red',
        self::FULFILLMENT_STATUS_PENDING => 'yellow',
        self::FULFILLMENT_STATUS_DELETE => 'orange',
    ];

    /** Payment status Constants */
    const PAYMENT_STATUS_PAID = 1;
    const PAYMENT_STATUS_UNPAID = 2;
    const PAYMENT_STATUS_DEPOSIT = 3;
    const PAYMENT_STATUS_DEFAULT = 4;

    const PAYMENT_STATUSES = [
        self::PAYMENT_STATUS_PAID,
        self::PAYMENT_STATUS_UNPAID,
        self::PAYMENT_STATUS_DEPOSIT,
        self::PAYMENT_STATUS_DEFAULT,
    ];

    const MAP_PAYMENT_STATUSES = [
        self::PAYMENT_STATUS_PAID => 'Paid',
        self::PAYMENT_STATUS_UNPAID => 'Unpaid',
        self::PAYMENT_STATUS_DEPOSIT => 'Deposit',
        self::PAYMENT_STATUS_DEFAULT => 'Default',
    ];

    const MAP_PAYMENT_COLORS = [
        self::PAYMENT_STATUS_PAID => 'green',
        self::PAYMENT_STATUS_UNPAID => 'red',
        self::PAYMENT_STATUS_DEPOSIT => 'yellow',
        self::PAYMENT_STATUS_DEFAULT => 'orange',
    ];

    /** Shipping Status constants */
    const SHIPPING_WAITING = 1;
    const SHIPPING_SHIPPED = 2;    
    const SHIPPING_DELIVERED = 3;
    const SHIPPING_RETURNED = 4;

    const MAP_SHIPPING_STATUSES = [
        self::SHIPPING_WAITING => 'Waiting',
        self::SHIPPING_SHIPPED => 'Shipped',
        self::SHIPPING_DELIVERED => 'Delivered',
        self::SHIPPING_RETURNED => 'Returned',
    ];

    /** Available countries */
    const COUNTRY_AU = 'AU';
    const COUNTRY_US = 'US';
    const COUNTRY_CA = 'CA';

    const MAP_COUNTRIES = [
        self::COUNTRY_AU => 'Australia',
        self::COUNTRY_US => 'America',
        self::COUNTRY_CA => 'Canada',
    ];

    /** Available bulk actions */
    const BULK_MARK_SHIPPED = 'mark_shipped';
    const BULK_MARK_DELIVERED = 'mark_delivered';
    const BULK_MARK_RETURNED = 'mark_returned';
    const BULK_MARK_LABOUR_PAID = 'mark_labour_paid';
    const BULK_EXPORT_CSV = 'export_csv';

    const MAP_BULK_ACTIONS = [
        self::BULK_MARK_SHIPPED => 'Mark as Shipped',
        self::BULK_MARK_DELIVERED => 'Mark as Delievered',
        self::BULK_MARK_RETURNED => 'Mark as Returned',
        self::BULK_MARK_LABOUR_PAID => 'Mark Paid Labour',
        self::BULK_EXPORT_CSV => 'Export as CSV',
    ];

    /** Available shipping services */
    const SHIPPING_ECONOMY = 1;
    const SHIPPING_EXPRESS = 2;
    const SHIPPING_ECONOMY_SIGNATURE = 3;
    const SHIPPING_EXPRESS_SIGNATURE = 4;

    const MAP_SHIPPING = [
        self::SHIPPING_ECONOMY => 'Economy',
        self::SHIPPING_EXPRESS => 'Express',
        self::SHIPPING_ECONOMY_SIGNATURE => 'Economy - Signature Required',
        self::SHIPPING_EXPRESS_SIGNATURE => 'Express - Signature Required',
    ];

}