<?php

namespace App\Models;

use App\Enums\SupplierEnum;
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
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier\Log as SupplierLog;
use App\Traits\ModelBootTrait;

class Supplier extends Model
{
    use HasFactory, ModelBootTrait;

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

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'supplier_id', 'id');
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

        return SupplierMeta::where('identifier', $identifier)
                        ->where('supplier_id', $this->id)
                        ->first();
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
        $meta = SupplierMeta::where('identifier', $identifier)
                        ->where('supplier_id', $this->id)
                        ->first();
                        
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

    public function addLog(?string $message): bool
    {
        $loggedInUser = Auth::user();

        $newLog = new SupplierLog([
            'target_id' => $this->id,
            'description' => $message,
            'action_by_id' => $loggedInUser->id ?? null,
        ]);

        return $newLog->save();
    }

    public static function boot()
    {
        self::modelBoot();
    }

    /** ALL PRIVATE FUNCTIONS */
    private static function afterCreated(?self $newItem)
    {
        $logDetails = $newItem->getDetailsForNewSupplierCreated();

        return $newItem->addLog(implode("\n- ", $logDetails));
    }

    private static function beforeUpdate(?self $newItem)
    {
        $oldItem = self::find($newItem->id);
        $logDetails = $newItem->getDetailsChangedForUpdateBoot($oldItem);
        if (empty($logDetails)) {
            return ;
        }
        
        return $newItem->addLog("Supplier's information has been updated as below:\n- " . implode("\n- ", $logDetails));
    }

    private function getDetailsForNewSupplierCreated(): ?array
    {
        $logDetails = ["The supplier #{$this->id} has been created with the following details:"];
        foreach (SupplierEnum::LOG_COLUMNS as $column => $title) {
            switch ($column) {
                case 'status':
                    $value = SupplierEnum::MAP_STATUSES[$this->{$column}] ?? 'Unknown';
                    break;
                case 'type':
                    $value = SupplierEnum::MAP_TYPES[$this->{$column}] ?? 'Unknown';
                    break;
                default:
                    $value = $this->{$column};
                    break;
            }

            $logDetails[] = "{$title}: {$value}";
        }

        return $logDetails;
    }

    private function getDetailsChangedForUpdateBoot($oldItem): ?array
    {
        $logDetails = [];
        foreach (SupplierEnum::LOG_COLUMNS as $column => $title) {
            if (is_null($oldItem->{$column}) || is_null($this->{$column})) {
                continue;
            }

            if ($oldItem->{$column} == $this->{$column}) {
                continue;
            }

            switch ($column) {
                case 'status':
                    $oldValue = SupplierEnum::MAP_STATUSES[$oldItem->{$column}] ?? 'Unknown';
                    $newValue = SupplierEnum::MAP_STATUSES[$this->{$column}] ?? 'Unknown';
                    break;
                case 'type':
                    $oldValue = SupplierEnum::MAP_TYPES[$oldItem->{$column}] ?? 'Unknown';
                    $newValue = SupplierEnum::MAP_TYPES[$this->{$column}] ?? 'Unknown';
                    break;
                default:
                    $oldValue = $oldItem->{$column};
                    $newValue = $this->{$column};
                    break;
            }

            $messageField = "{$title}: {$oldValue} -> {$newValue}";
            $logDetails[] = $messageField;
        }

        return $logDetails;
    }
}
