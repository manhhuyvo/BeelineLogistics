<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Enums\SupplierMetaEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meta extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'identifier',
        'value',
    ];

    protected $casts = [
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function getValue()
    {
        if (in_array($this->indentifer, SupplierMetaEnum::ARRAY_TYPE_META)) {
            return !empty($this->value) ? explode(',', $this->value) : [];
        }

        return $this->value;
    }
}
