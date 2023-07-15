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

    const LEVEL_DIRECTOR = 1;
    const LEVEL_ACCOUNTANT = 2;
    const LEVEL_SALES = 3;
    const LEVEL_CUSTOMER_SERVICE = 4;
    const LEVEL_IT = 5;

    const STATUS_ACTIVE = 1;
    const STATUS_CANCEL = 2;
    const STATUS_PENDING = 3;
    const STATUS_DELETE = 4;

    const TYPE_STAFF = 'staffs';
    const TYPE_CUSTOMER = 'customers';
    const TYPE_SUPPLIER = 'suppliers';

    const USER_TYPES = [
        self::TYPE_STAFF,
        self::TYPE_CUSTOMER,
        self::TYPE_SUPPLIER,
    ];

    const USER_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_CANCEL,
        self::STATUS_PENDING,
        self::STATUS_DELETE,
    ];

    const MAP_TYPES = [
        self::TYPE_STAFF => 'Staff',
        self::TYPE_CUSTOMER => 'Customer',
        self::TYPE_SUPPLIER => 'Supplier',
    ];

    const MAP_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_CANCEL => 'Cancel',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_DELETE => 'Delete',
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
}
