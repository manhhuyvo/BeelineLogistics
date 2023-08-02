<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Invoice;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'customer_id',
        'staff_id',
        'full_name',
        'phone',
        'address',
        'price_configs',
        'default_sender',
        'default_receiver',
        'type',
        'company',
        'status',
        'note',
    ];

    protected $casts = [
        'default_sender' => 'array',
        'default_receiver' => 'array',
        'price_configs' => 'array',
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    const RECEIVER_HCM_ZONE = 1;
    const RECEIVER_HANOI_ZONE = 2;
    const RECEIVER_REGIONAL_ZONE = 3;

    const MAP_ZONES = [
        self::RECEIVER_HCM_ZONE => "HCM",
        self::RECEIVER_HANOI_ZONE => "HANOI",
        self::RECEIVER_REGIONAL_ZONE => "REGIONAL",
    ];

    const RECEIVER_ZONES = [
        self::RECEIVER_HCM_ZONE,
        self::RECEIVER_HANOI_ZONE,
        self::RECEIVER_REGIONAL_ZONE,
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_CANCEL = 2;
    const STATUS_PENDING = 3;

    const TYPE_RETAILER = 1;
    const TYPE_WHOLESALER = 2;
    const TYPE_BUSINESS = 3;

    const CUSTOMER_TYPES = [
        self::TYPE_RETAILER,
        self::TYPE_WHOLESALER,
        self::TYPE_BUSINESS,
    ];

    const CUSTOMER_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_CANCEL,
        self::STATUS_PENDING,
    ];

    const MAP_STATUSES_COLOR = [
        self::STATUS_ACTIVE => 'green',
        self::STATUS_PENDING => 'yellow',
        self::STATUS_CANCEL => 'red',
    ];

    const MAP_TYPES = [
        self::TYPE_RETAILER => 'Retailer',
        self::TYPE_WHOLESALER => 'Wholesaler',
        self::TYPE_BUSINESS => 'Business',
    ];

    const MAP_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_CANCEL => 'Cancel',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PENDING => 'Pending',
    ];

    public function account(): HasOne
    {
        return $this->hasOne(User::class, 'customer_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'id');
    }
}
