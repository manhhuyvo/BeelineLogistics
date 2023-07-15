<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use App\Models\Bill;


class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'full_name',
        'phone',
        'address',
        'type',
        'company',
        'status',
        'note',
    ];

    const STATUS_CURRENT = 1;
    const STATUS_TEMPORARY_STOP = 2;
    const STATUS_STOP = 3;

    const TYPE_AUSTRALIA = 1;
    const TYPE_USA = 2;
    const TYPE_CANADA = 3;
    const TYPE_KOREA = 4;

    const SUPPLIER_TYPES = [
        self::TYPE_AUSTRALIA,
        self::TYPE_USA,
        self::TYPE_CANADA,
        self::TYPE_KOREA,
    ];

    const SUPPLIER_STATUSES = [
        self::STATUS_CURRENT,
        self::STATUS_TEMPORARY_STOP,
        self::STATUS_STOP,
    ];

    const MAP_TYPES = [
        self::TYPE_AUSTRALIA => 'Australia',
        self::TYPE_USA => 'America',
        self::TYPE_CANADA => 'Canada',
        self::TYPE_KOREA => 'Korea',
    ];

    const MAP_STATUSES = [
        self::STATUS_CURRENT => 'Current',
        self::STATUS_TEMPORARY_STOP => 'Temporary Stop',
        self::STATUS_STOP => 'Stop',
    ];

    public function account(): HasOne
    {
        return $this->hasOne(User::class, 'supplier_id', 'id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'supplier_id', 'id');
    }
}
