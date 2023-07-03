<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
