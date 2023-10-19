<?php

namespace App\Models\Bill;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Bill;

class Item extends Model
{
    use HasFactory;

    protected $table = 'bill_items';

    protected $fillable = [
        'bill_id',
        'description',
        'amount',
        'note',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class, 'bill_id', 'id');
    }
}
