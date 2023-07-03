<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayCheck extends Model
{
    use HasFactory;

    protected $table = 'salary_paychecks';

    protected $fillable = [
        'staff_id',
        'amount',
        'status',
        'note',
    ];
}
