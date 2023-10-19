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

    const NAME_CHECKOUT_PRODUCTS = 'checkout_product';
    const NAME_ORDER_HISTORY = 'order_history';

    const META_NAMES = [
        self::NAME_CHECKOUT_PRODUCTS,
        self::NAME_ORDER_HISTORY,
    ];

    const MAP_NAMES = [
        self::NAME_CHECKOUT_PRODUCTS => 'Checkout Products',
        self::NAME_ORDER_HISTORY => 'Order History',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
