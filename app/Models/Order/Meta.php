<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
