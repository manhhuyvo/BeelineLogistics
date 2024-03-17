<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Transaction;
use App\Models\Staff;
use App\Models\User;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'transaction_id',
        'user_id', // User who added this payment
        'staff_id', // Staff who appoved or declined or deleted this payment
        'amount',
        'description',
        'payment_method',
        'payment_receipt',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'created_at' => 'date:d/m/Y H:i:s',        
        'updated_at' => 'date:d/m/Y H:i:s',
        'payment_date' => 'date:d/m/Y H:i:s',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
