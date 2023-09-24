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
        'invoice_id',
        'amount',
        'description',
        'payment_method',
        'payment_date',
    ];

    const PAYMENT_METHOD_TRANSFER = '1';
    const PAYMENT_METHOD_CASH = '2';
    
    const MAP_PAYMENT_METHOD = [
        self::PAYMENT_METHOD_TRANSFER => 'Bank Transfer',
        self::PAYMENT_METHOD_CASH => 'Cash',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
