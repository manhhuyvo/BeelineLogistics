<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Fulfillment\ProductPayment as FulfillmentProductPayment;
use App\Models\SupportTicket;
use App\Models\SupportTicket\Comment as SupportTicketComment;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;

    protected $table = "users";

    protected $fillable = [
        'username',
        'password',
        'target',
        'staff_id',
        'customer_id',
        'supplier_id',
        'level',
        'status',
        'note',
    ];

    protected $casts = [
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    // Staff User Level
    const LEVEL_DIRECTOR = 1;
    const LEVEL_ACCOUNTANT = 2;
    const LEVEL_SALES = 3;
    const LEVEL_CUSTOMER_SERVICE = 4;
    const LEVEL_IT = 5;

    // Customer User Level
    const LEVEL_CUSTOMER = 20;
    // Supplier User Level
    const LEVEL_SUPPLIER = 30;

    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;
    const STATUS_SUSPENDED = 3;
    const STATUS_DELETE = 4;

    const TARGET_STAFF = 'staffs';
    const TARGET_CUSTOMER = 'customers';
    const TARGET_SUPPLIER = 'suppliers';

    const USER_TARGETS = [
        self::TARGET_STAFF,
        self::TARGET_CUSTOMER,
        self::TARGET_SUPPLIER,
    ];

    const USER_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_PENDING,
        self::STATUS_SUSPENDED,
        self::STATUS_DELETE,
    ];

    const USER_LEVELS = [
        self::LEVEL_DIRECTOR,
        self::LEVEL_ACCOUNTANT,
        self::LEVEL_SALES,
        self::LEVEL_CUSTOMER_SERVICE,
        self::LEVEL_IT,
        self::LEVEL_CUSTOMER,
        self::LEVEL_SUPPLIER,

    ];

    const MAP_USER_LEVELS = [
        self::LEVEL_DIRECTOR => "Director",
        self::LEVEL_ACCOUNTANT => "Accountant",
        self::LEVEL_SALES => "Sales",
        self::LEVEL_CUSTOMER_SERVICE => "Customer Service",
        self::LEVEL_IT => "IT",
        self::LEVEL_CUSTOMER => "Customer",
        self::LEVEL_SUPPLIER => "Supplier",
    ];

    const MAP_USER_STAFF_LEVELS = [
        self::LEVEL_DIRECTOR => "Director",
        self::LEVEL_ACCOUNTANT => "Accountant",
        self::LEVEL_SALES => "Sales",
        self::LEVEL_CUSTOMER_SERVICE => "Customer Service",
        self::LEVEL_IT => "IT",
    ];

    const MAP_TARGETS = [
        self::TARGET_STAFF => 'Staff',
        self::TARGET_CUSTOMER => 'Customer',
        self::TARGET_SUPPLIER => 'Supplier',
    ];

    const MAP_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_SUSPENDED => 'Suspended',
        self::STATUS_DELETE => 'Delete',
    ];

    const MAP_STATUSES_COLOR = [
        self::STATUS_ACTIVE => 'green',
        self::STATUS_PENDING => 'yellow',
        self::STATUS_SUSPENDED => 'red',
        self::STATUS_DELETE => 'gray',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function fulfillmentProductPayments(): HasMany
    {
        return $this->hasMany(FulfillmentProductPayment::class, 'user_id', 'id');
    }

    public function supportTicketsCreated(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'created_user_id', 'id');
    }

    public function supportTicketsSolved(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'solved_user_id', 'id');
    }

    public function ticketComments(): HasMany
    {
        return $this->hasMany(SupportTicketComment::class, 'user_id', 'id');
    }

    public function getUserOwner()
    {
        if ($this->staff_id != 0) {
            return $this->staff;
        }

        if ($this->customer_id != 0) {
            return $this->customer;
        }

        if ($this->supplier != 0) {
            return $this->supplier;
        }

        return null;
    }

    public function isStaff()
    {
        return $this->target == self::TARGET_STAFF && $this->staff_id != 0;
    }

    public function isCustomer()
    {
        return $this->target == self::TARGET_CUSTOMER && $this->customer_id != 0;
    }

    public function isSupplier()
    {
        return $this->target == self::TARGET_SUPPLIER && $this->supplier_id != 0;
    }
}
