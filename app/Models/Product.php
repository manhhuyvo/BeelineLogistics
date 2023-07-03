<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product\Group as ProductGroup;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'group_id',
        'address',
        'status',
        'note',
        'price_configs',
    ];

    protected $casts = [
        'price_configs' => 'array',
    ];

    public function productGroup(): BelongsTo
    {
        return $this->belongsTo(ProductGroup::class, 'group_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'product_id', 'id');
    }
}
