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
        'status',
        'note',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'group_id', 'id');
    }
}
