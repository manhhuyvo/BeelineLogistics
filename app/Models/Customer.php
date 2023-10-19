<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\CustomerMetaEnum;
use App\Models\User;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Invoice;
use App\Models\Fulfillment;
use App\Models\Customer\Meta as CustomerMeta;
use App\Models\CustomerSupplierMapper;

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

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'customer_id', 'id');
    }

    public function fulfillments(): HasMany
    {
        return $this->hasMany(Fulfillment::class, 'customer_id', 'id');
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'customer_id', 'id');
    }

    public function meta(): HasMany
    {
        return $this->hasMany(CustomerMeta::class, 'customer_id', 'id');
    }

    public function supplierMapper(): HasMany
    {
        return $this->hasMany(CustomerSupplierMapper::class, 'customer_id', 'id');
    }

    // Multiple suppliers can be returned
    public function getSuppliersByKeys(string $country = '', string $service = '')
    {
        if (empty($country) && empty($service)) {
            return null;
        }

        // Initiate mapper by customer ID
        $mapper = CustomerSupplierMapper::where('customer_id', $this->id);

        // Assign country
        if (!empty($country)) {
            $mapper->where('country', $country);
        }

        // Assign service
        if (!empty($service)) {
            $mapper->where('service', $service);
        }

        $mapper = $mapper->get();
        if (!$mapper) {
            return null;
        }

        return $mapper;
    }

    public function getMeta(string $identifier = '')
    {
        if (!in_array($identifier, CustomerMetaEnum::VALID_META)) {
            return null;
        }

        return CustomerMeta::where('identifier', $identifier)
                ->where('customer_id', $this->id)
                ->first();
    }

    public function createMeta(string $identifier, string $value)
    {
        if (empty($identifier) || empty($value)) {
            return false;
        }

        if (!in_array($identifier, CustomerMetaEnum::VALID_META) || !is_string($value)) {
            return false;
        }

        // If meta already exists, then we update it
        $meta = CustomerMeta::where('identifier', $identifier)
                    ->where('customer_id', $this->id)
                    ->first();

        if ($meta) {
            $meta->value = $value;

            if (!$meta->update()) {
                return false;
            }
    
            return $meta;
        }

        // Otherwise if meta not exists, then we create
        $meta = new CustomerMeta([
            'customer_id' => $this->id,
            'identifier' => $identifier,
            'value' => $value,
        ]);

        if (!$meta->save()) {
            return false;
        }

        return $meta;
    }
}
