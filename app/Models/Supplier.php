<?php

namespace App\Models;

use App\Enums\SupplierMetaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use App\Models\Bill;
use App\Models\Fulfillment;
use App\Models\Supplier\Meta as SupplierMeta;
use App\Models\CustomerSupplierMapper;


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

    protected $casts = [
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    public function account(): HasOne
    {
        return $this->hasOne(User::class, 'supplier_id', 'id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'supplier_id', 'id');
    }

    public function fulfillments(): HasMany
    {
        return $this->hasMany(Fulfillment::class, 'supplier_id', 'id');
    }

    public function meta(): HasMany
    {
        return $this->hasMany(SupplierMeta::class, 'supplier_id', 'id');
    }

    public function customerMapper(): HasMany
    {
        return $this->hasMany(CustomerSupplierMapper::class, 'supplier_id', 'id');
    }

    // Many customers can be returned
    public function getCustomersByKeys(string $country = '', string $service = '')
    {
        if (empty($country) && empty($service)) {
            return null;
        }

        $mapper = CustomerSupplierMapper::where('supplier_id', $this->id);

        // Assign country
        if (!empty($country)) {
            $mapper->where('country', $country);
        }

        // Assign service
        if (!empty($service)) {
            $mapper->where('service', $service);
        }

        $mapper = $mapper->get();
        if (!$mapper) {
            return null;
        }

        return $mapper;
    }

    public function getMeta(string $identifier = '')
    {
        if (!in_array($identifier, SupplierMetaEnum::VALID_META)) {
            return null;
        }

        return SupplierMeta::where('identifier', $identifier)->first();
    }

    public function createMeta(string $identifier, string $value)
    {
        if (empty($identifier) || empty($value)) {
            return false;
        }

        if (!in_array($identifier, SupplierMetaEnum::VALID_META) || !is_string($value)) {
            return false;
        }

        // If meta already exists, then we update it
        $meta = SupplierMeta::where('identifier', $identifier)->first();
        if ($meta) {
            $meta->value = $value;

            if (!$meta->update()) {
                return false;
            }
    
            return $meta;
        }

        // Otherwise if meta not exists, then we create
        $meta = new SupplierMeta([
            'supplier_id' => $this->id,
            'identifier' => $identifier,
            'value' => $value,
        ]);

        if (!$meta->save()) {
            return false;
        }

        return $meta;
    }
}
