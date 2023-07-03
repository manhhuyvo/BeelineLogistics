<?php

namespace App\Models\Bill;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'bill_items';

    protected $fillable = [
        'bill_id',
        'description',
        'amount',
        'note',
    ];
}
