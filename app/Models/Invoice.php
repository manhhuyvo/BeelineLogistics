<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Customer;
use App\Models\Invoice\Item as InvoiceItem;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'customer_id',
        'total_amount',
        'outstanding_amount',
        'status',
        'note',
    ];

    const STATUS_PAID = 1;
    const STATUS_UNPAID = 2;
    const STATUS_CANCEL = 3;    
    const STATUS_DELETE = 4;

    const INVOICE_STATUSES = [
        self::STATUS_PAID,
        self::STATUS_UNPAID,
        self::STATUS_CANCEL,
        self::STATUS_DELETE,
    ];

    const MAP_STATUSES = [
        self::STATUS_PAID => 'Paid',
        self::STATUS_UNPAID => 'Unpaid',
        self::STATUS_CANCEL => 'Cancel',
        self::STATUS_DELETE => 'Delete',
    ];

    protected $casts = [
        'price_configs' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
