<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Staff;
use App\Models\SalaryPayCheck\Item as SalaryItem;

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

    public function items(): HasMany
    {
        return $this->hasMany(SalaryItem::class, 'paycheck_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }
}
