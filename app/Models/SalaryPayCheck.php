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

    const STATUS_PAID = 1;
    const STATUS_UNPAID = 2;
    const STATUS_CANCEL = 3;    
    const STATUS_DELETE = 4;

    const PAYCHECK_STATUSES = [
        self::STATUS_PAID,
        self::STATUS_UNPAID,
        self::STATUS_CANCEL,
        self::STATUS_DELETE,
    ];

    const MAP_STATUSES = [
        self::STATUS_PAID => 'Paid',
        self::STATUS_UNPAID => 'Unpaid',
        self::STATUS_CANCEL => 'Cancel',
        self::STATUS_DELETE => 'Delete',
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
