<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Supplier;
use App\Models\Bill\Item as BillItem;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';

    protected $fillable = [
        'supplier_id',
        'total_amount',
        'outstanding_amount',
        'status',
        'note',
    ];

    const STATUS_PAID = 1;
    const STATUS_UNPAID = 2;
    const STATUS_CANCEL = 3;    
    const STATUS_DELETE = 4;

    const BILL_STATUSES = [
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

    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class, 'bill_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
