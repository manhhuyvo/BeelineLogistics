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

    public function account(): HasOne
    {
        return $this->hasOne(User::class, 'target_id', 'id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'supplier_id', 'id');
    }
}
