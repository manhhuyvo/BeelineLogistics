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
use App\Models\Supplier;

class ProductPayment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'fulfillment_id',
        'user_id', // User who added this payment to the fulfillment
        'approved_by', // Staff or supplier who approves this
        'amount',
        'description',
        'path', // Path where the image of payment receipt is stored
        'payment_method',
        'status', // Status if this payment has been approved
        'payment_date',
    ];

    const PAYMENT_METHOD_TRANSFER = '1';
    const PAYMENT_METHOD_CASH = '2';
    
    const MAP_PAYMENT_METHOD = [
        self::PAYMENT_METHOD_TRANSFER => 'Bank Transfer',
        self::PAYMENT_METHOD_CASH => 'Cash',
    ];

    const STATUS_APPROVED = '1';
    const STATUS_PENDING = '2';
    const STATUS_REJECTED = '3';
    const STATUS_DELETED = '4';
    
    const MAP_STATUSES = [
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_DELETED => 'Deleted',
    ];
    
    const MAP_STATUS_COLORS = [
        self::STATUS_APPROVED => 'green',
        self::STATUS_PENDING => 'blue',
        self::STATUS_REJECTED => 'red',
        self::STATUS_DELETED => 'gray',
    ];

    public function fulfillment(): BelongsTo
    {
        return $this->belongsTo(Fulfillment::class, 'fulfillment_id', 'id');
    }

    public function approval(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'confirmed_by', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
