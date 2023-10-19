<?php

namespace App\Models\SalaryPayCheck;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SalaryPayCheck;

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

    public function paycheck(): BelongsTo
    {
        return $this->belongsTo(SalaryPayCheck::class, 'paycheck_id', 'id');
    }
}
