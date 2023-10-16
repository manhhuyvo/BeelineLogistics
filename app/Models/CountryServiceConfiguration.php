<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\CurrencyAndCountryEnum;
use App\Enums\GeneralEnum;
use App\Models\Supplier;
use Illuminate\Support\Arr;

class CountryServiceConfiguration extends Model
{
    use HasFactory;
    
    protected $table = "country_service_configuration";

    protected $fillable = [
        'default_supplier_id',
        'country',
        'service',
    ];

    protected $casts = [
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'default_supplier_id', 'id');
    }

    public function getCountry(bool $formatted = false)
    {
        return $formatted ? (CurrencyAndCountryEnum::MAP_COUNTRIES[$this->country] ?? 'Unknown') : $this->country;
    }

    public function getService(bool $formatted = false)
    {
        return $formatted ? (GeneralEnum::MAP_SERVICES[$this->service] ?? 'Unknown') : $this->service;
    }
}
