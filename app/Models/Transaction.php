<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Invoice;
use App\Models\Bill;
use App\Models\SalaryPayCheck;
use App\Models\Payment;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'target',
        'target_id',
        'payment_id',
        'amount',
        'description',
        'note',
        'transaction_date',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'target_id', 'id');
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class, 'target_id', 'id');
    }

    public function paycheck(): BelongsTo
    {
        return $this->belongsTo(SalaryPayCheck::class, 'target_id', 'id');
    }
}
