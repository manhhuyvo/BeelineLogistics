<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';

    protected $fillable = [
        'supplier_id',
        'total_amount',
        'outstanding_amount',
        'status',
        'note',
    ];
}
