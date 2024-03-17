<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
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
        'amount',
        'description',
        'note',
        'transaction_date',
    ];

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'transaction_id', 'id');
    }

    public function invoice(): ?Invoice
    {
        return Invoice::find($this->target_id);
    }

    public function bill(): ?Bill
    {
        return Bill::find($this->target_id);
    }

    public function paycheck(): ?SalaryPayCheck
    {
        return SalaryPayCheck::find($this->target_id);
    }
}
