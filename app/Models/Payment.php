<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Supplier;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'type',
        'target',
        'target_id',
        'amount',
        'description',
        'note',
        'payment_method',
        'payment_date',
    ];

    const TYPE_RECEIVE_PAYMENT = 'receive_payment';
    const TYPE_PAY_BILL = 'pay_bill';
    const TYPE_PAY_SALARY = 'pay_salary';
    const TYPE_PAY_BONUS = 'pay_bonus';
    const TYPE_PAY_REFUND = 'pay_refund';

    const PAYMENT_TYPES = [
        self::TYPE_RECEIVE_PAYMENT,
        self::TYPE_PAY_BILL,
        self::TYPE_PAY_SALARY,
        self::TYPE_PAY_BONUS,
        self::TYPE_PAY_REFUND,
    ];

    const MAP_TYPES = [
        self::TYPE_RECEIVE_PAYMENT => 'Receive Payment',
        self::TYPE_PAY_BILL => 'Pay Bill',
        self::TYPE_PAY_SALARY => 'Pay Salary',
        self::TYPE_PAY_BONUS => 'Pay Bonus',
        self::TYPE_PAY_REFUND => 'PAY Refund',
    ];

    const PAYMENT_METHOD_TRANSFER = '1';
    const PAYMENT_METHOD_CASH = '2';
    
    const MAP_PAYMENT_METHOD = [
        self::PAYMENT_METHOD_TRANSFER => 'Bank Transfer',
        self::PAYMENT_METHOD_CASH => 'Cash',
    ];

    const TARGET_STAFF = 'staffs';
    const TARGET_SUPPLIER = 'suppliers';
    const TARGET_CUSTOMER = 'customers';

    const PAYMENT_TARGET = [
        self::TARGET_STAFF,
        self::TARGET_SUPPLIER,
        self::TARGET_CUSTOMER,
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payment_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'target_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'target_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'target_id', 'id');
    }
}
