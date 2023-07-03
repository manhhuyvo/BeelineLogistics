<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
