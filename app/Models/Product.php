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
        'stock',
        'group_id',
        'status',
        'note',
        'price_configs',
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    const PRODUCT_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];

    const MAP_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];
    
    const MAP_STATUS_COLORS = [        
        self::STATUS_ACTIVE => 'green',
        self::STATUS_INACTIVE => 'red',
    ];

    const UNIT_AUD = 'AUD';    
    const UNIT_VND = 'VND';
    const UNIT_USD = 'USD';
    const UNIT_CAD = 'CAD';

    const UNITS = [
        self::UNIT_AUD,
        self::UNIT_VND,
        self::UNIT_USD,
        self::UNIT_CAD,
    ];

    protected $casts = [
        'price_configs' => 'array',
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
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
