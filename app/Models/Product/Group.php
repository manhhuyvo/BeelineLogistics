<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $table = 'product_groups';

    protected $fillable = [
        'name',
        'description',
        'note',
    ];

    const NAME_IMPORT = 'import';
    const NAME_EXPORT = 'export';
    const NAME_PURCHASE_CHECKOUT = 'checkout';

    const GROUP_NAMES = [
        self::NAME_IMPORT,
        self::NAME_EXPORT,
        self::NAME_PURCHASE_CHECKOUT,
    ];

    const MAP_NAMES = [
        self::NAME_IMPORT => 'Import',
        self::NAME_EXPORT => 'Export',
        self::NAME_PURCHASE_CHECKOUT => 'Purchase and Checkout',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'group_id', 'id');
    }
}
