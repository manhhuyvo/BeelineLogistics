<?php

namespace App\Models\Supplier;

use App\Enums\CurrencyAndCountryEnum;
use App\Enums\GeneralEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Enums\SupplierMetaEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meta extends Model
{
    use HasFactory;
    
    protected $table = "supplier_meta";

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
        if (in_array($this->identifier, SupplierMetaEnum::ARRAY_TYPE_META)) {
            return !empty($this->value) ? explode(',', $this->value) : [];
        }

        return $this->value;
    }

    public function getFormattedValue(): string
    {
        $arrayValues = $this->getValue();
        if (empty($arrayValues)) {
            return "";
        }

        switch ($this->identifier) {
            case SupplierMetaEnum::META_AVAILABLE_COUNTRY:
                return collect($arrayValues)
                    ->map(function ($eachValue) {
                        return CurrencyAndCountryEnum::MAP_COUNTRIES[$eachValue] ?? 'Unknown';
                    })
                    ->implode(', ');
                break;
            case SupplierMetaEnum::META_AVAILABLE_SERVICE:
                return collect($arrayValues)
                    ->map(function ($eachValue) {
                        return GeneralEnum::MAP_SERVICES[$eachValue] ?? 'Unknown';
                    })
                    ->implode(', ');
                break;
        }
    }
}
