<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Product;

class Fulfillment extends Model
{
    use HasFactory;

    protected $table = 'fulfillments';

    protected $fillable = [
        'product_configs',
        'customer_id',
        'staff_id',
        'name',
        'phone',
        'address',
        'address2',
        'suburb',
        'state',
        'postcode',
        'country',
        'labour_configs',
        'shipping_type',
        'postage',
        'tracking_number',
        'total_product_amount',
        'total_labour_amount',
        'fulfillment_status',
        'product_payment_status',
        'labour_payment_status',
        'note',
    ];

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

    /** Available countries */
    const COUNTRY_AU = 'AU';
    const COUNTRY_US = 'US';
    const COUNTRY_CA = 'CA';

    const MAP_COUNTRIES = [
        self::COUNTRY_AU => 'Australia',
        self::COUNTRY_US => 'America',
        self::COUNTRY_CA => 'Canada',
    ];

    protected $casts = [
        'product_configs' => 'array',
        'labour_configs' => 'array',
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
