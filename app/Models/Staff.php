<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Customer;
use App\Models\User;
use App\Models\SalaryPayCheck;
use App\Models\Order;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staffs';

    protected $fillable = [
        'full_name',
        'phone',
        'address',
        'dob',
        'position',
        'status',
        'note',
    ];

    protected $casts = [
        'salary_configs' => 'array',
    ];

    const STATUS_CURRENT = 1;
    const STATUS_TEMPORARY_OFF = 2;
    const STATUS_OFF = 3;

    const POSITION_DIRECTOR = 1;
    const POSITION_ACCOUNTANT = 2;
    const POSITION_SALES = 3;
    const POSITION_CUSTOMER_SERVICE = 4;
    const POSITION_IT = 5;

    const STAFF_POSITIONS = [
        self::POSITION_DIRECTOR,
        self::POSITION_ACCOUNTANT,
        self::POSITION_SALES,
        self::POSITION_CUSTOMER_SERVICE,
        self::POSITION_IT,
    ];

    const STAFF_STATUSES = [
        self::STATUS_CURRENT,
        self::STATUS_TEMPORARY_OFF,
        self::STATUS_OFF,
    ];

    const MAP_POSITIONS = [
        self::POSITION_DIRECTOR => 'Director',
        self::POSITION_ACCOUNTANT => 'Accountant',
        self::POSITION_SALES => 'Sales',
        self::POSITION_CUSTOMER_SERVICE => 'Customer Service',
        self::POSITION_IT => 'IT',
    ];

    const MAP_STATUSES = [
        self::STATUS_CURRENT => 'Current',
        self::STATUS_TEMPORARY_OFF => 'Temporary Off',
        self::STATUS_OFF => 'Off',
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'staff_id', 'id');
    }

    public function account(): HasOne
    {
        return $this->hasOne(User::class, 'target_id', 'id');
    }

    public function paychecks(): HasMany
    {
        return $this->hasMany(SalaryPayCheck::class, 'staff_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'staff_id', 'id');
    }
}
