<?php

namespace App\Models\SalaryPayCheck;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'salary_items';

    protected $fillable = [
        'paycheck_id',
        'amount',
        'description',
        'note',
    ];
}
