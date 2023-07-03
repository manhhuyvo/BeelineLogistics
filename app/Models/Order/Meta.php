<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Order;

class Meta extends Model
{
    use HasFactory;

    protected $table = 'order_meta';

    protected $fillable = [
        'order_id',
        'name',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
