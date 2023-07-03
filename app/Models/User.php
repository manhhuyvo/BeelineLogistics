<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Supplier;

class User extends Model
{
    use HasFactory;

    protected $table = "users";

    protected $fillable = [
        'username',
        'password',
        'target',
        'target_id',
        'level',
        'status',
        'note',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'target_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'target_id', 'id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'target_id', 'id');
    }
}
