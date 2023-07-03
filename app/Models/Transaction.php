<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
