<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'full_name',
        'phone',
        'address',
        'dob',
        'position',
        'status',
        'note',
    ];

    protected $casts = [
        'salary_configs' => 'array',
    ];
}
