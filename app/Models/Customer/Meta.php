<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CustomerMetaEnum;
use App\Models\Customer;
use App\Models\Customer\Meta as CustomerMeta;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meta extends Model
{
    use HasFactory;
    
    protected $table = "customer_meta";

    protected $fillable = [
        'customer_id',
        'identifier',
        'value',
    ];

    protected $casts = [
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function getValue()
    {
        if (in_array($this->identifier, CustomerMetaEnum::ARRAY_TYPE_META)) {
            return !empty($this->value) ? explode(',', $this->value) : [];
        }

        return $this->value;
    }
}
