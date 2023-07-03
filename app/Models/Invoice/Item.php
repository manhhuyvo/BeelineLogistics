<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'description',
        'amount',
        'note',
    ];
}
