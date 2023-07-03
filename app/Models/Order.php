<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'product_id',
        'customer_id',
        'staff_id',
        'sender_name',
        'sender_phone',
        'sender_address',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'config_type',
        'tracking_numbers',
        'total_amount',
        'status',
        'note',
    ];

    protected $casts = [
        'config_type' => 'array',
    ];
}
