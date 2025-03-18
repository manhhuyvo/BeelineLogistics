<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Customer;
use App\Models\User;

class Log extends Model
{
    use HasFactory;
    
    protected $table = "customer_logs";

    protected $fillable = [
        'target_id',
        'description',
        'action_by_id',
    ];

    protected $casts = [
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    public function target(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'target_id', 'id');
    }

    public function action_user(): BelongsTo
    {        
        return $this->belongsTo(User::class, 'action_by_id', 'id');
    }
}
