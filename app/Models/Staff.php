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

    protected $table = 'users';

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
