<?php

namespace App\Models\Fulfillment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Fulfillment;
use App\Models\Staff;
use App\Models\User;
use App\Models\Supplier;

class ProductPayment extends Model
{
    use HasFactory;

    protected $table = 'fulfillment_product_payment';

    protected $fillable = [
        'fulfillment_id',
        'user_id', // User who added this payment to the fulfillment
        'staff_id', // Staff who appoved or declined or deleted this payment
        'amount',
        'description',
        'payment_receipt', // Path where the image of payment receipt is stored
        'payment_method',
        'status', // Status if this payment has been approved
        'payment_date',
    ];

    protected $casts = [
        'created_at' => 'date:d/m/Y',        
        'updated_at' => 'date:d/m/Y',
        'payment_date' => 'date:d/m/Y',
    ];

    public function fulfillment(): BelongsTo
    {
        return $this->belongsTo(Fulfillment::class, 'fulfillment_id', 'id');
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
