<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'customer_id',
        'staff_id',
        'full_name',
        'phone',
        'address',
        'default_sender',
        'default_receiver',
        'type',
        'company',
        'status',
        'note',
    ];

    protected $casts = [
        'default_sender' => 'array',
        'default_receiver' => 'array',
    ];
}
