<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Order\Meta as OrderMeta;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SupportTicket;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'product_id',
        'customer_id',
        'staff_id',
        'sender_name',
        'sender_phone',
        'sender_address',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'config_type',
        'tracking_numbers',
        'total_amount',
        'status',
        'note',
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_CANCEL = 2;
    const STATUS_PENDING = 3;
    const STATUS_DELETE = 4;

    const ORDER_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_CANCEL,
        self::STATUS_PENDING,
        self::STATUS_DELETE,
    ];

    const MAP_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_CANCEL => 'Cancel',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_DELETE => 'Delete',
    ];

    protected $casts = [
        'config_type' => 'array',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function meta(): HasMany
    {
        return $this->hasMany(OrderMeta::class, 'order_id', 'id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(SupportTicket::class, 'ticket_order_maps', 'order_id', 'ticket_id');
    }
}
