<?php

namespace App\Models;

use App\Enums\CustomerEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\CustomerMetaEnum;
use App\Enums\SupplierEnum;
use App\Models\User;
use App\Models\Order;
use App\Models\Staff;
use App\Models\Invoice;
use App\Models\Fulfillment;
use App\Models\Customer\Meta as CustomerMeta;
use App\Models\CustomerSupplierMapper;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer\Log as CustomerLog;
use App\Traits\ModelBootTrait;

class Customer extends Model
{
    use HasFactory, ModelBootTrait;

    protected $table = 'customers';

    protected $fillable = [
        'customer_id',
        'staff_id',
        'full_name',
        'phone',
        'address',
        'price_configs',
        'default_sender',
        'default_receiver',
        'type',
        'company',
        'status',
        'note',
    ];

    protected $casts = [
        'default_sender' => 'array',
        'default_receiver' => 'array',
        'price_configs' => 'array',
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    const RECEIVER_HCM_ZONE = 1;
    const RECEIVER_HANOI_ZONE = 2;
    const RECEIVER_REGIONAL_ZONE = 3;

    const MAP_ZONES = [
        self::RECEIVER_HCM_ZONE => "HCM",
        self::RECEIVER_HANOI_ZONE => "HANOI",
        self::RECEIVER_REGIONAL_ZONE => "REGIONAL",
    ];

    const RECEIVER_ZONES = [
        self::RECEIVER_HCM_ZONE,
        self::RECEIVER_HANOI_ZONE,
        self::RECEIVER_REGIONAL_ZONE,
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_CANCEL = 2;
    const STATUS_PENDING = 3;

    const TYPE_RETAILER = 1;
    const TYPE_WHOLESALER = 2;
    const TYPE_BUSINESS = 3;

    const CUSTOMER_TYPES = [
        self::TYPE_RETAILER,
        self::TYPE_WHOLESALER,
        self::TYPE_BUSINESS,
    ];

    const CUSTOMER_STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_CANCEL,
        self::STATUS_PENDING,
    ];

    const MAP_STATUSES_COLOR = [
        self::STATUS_ACTIVE => 'green',
        self::STATUS_PENDING => 'yellow',
        self::STATUS_CANCEL => 'red',
    ];

    const MAP_TYPES = [
        self::TYPE_RETAILER => 'Retailer',
        self::TYPE_WHOLESALER => 'Wholesaler',
        self::TYPE_BUSINESS => 'Business',
    ];

    const MAP_STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_CANCEL => 'Cancel',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PENDING => 'Pending',
    ];

    public function account(): HasOne
    {
        return $this->hasOne(User::class, 'customer_id', 'id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'customer_id', 'id');
    }

    public function fulfillments(): HasMany
    {
        return $this->hasMany(Fulfillment::class, 'customer_id', 'id');
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'customer_id', 'id');
    }

    public function meta(): HasMany
    {
        return $this->hasMany(CustomerMeta::class, 'customer_id', 'id');
    }

    public function supplierMapper(): HasMany
    {
        return $this->hasMany(CustomerSupplierMapper::class, 'customer_id', 'id');
    }

    // Multiple suppliers can be returned
    public function getSuppliersByKeys(string $country = '', string $service = '')
    {
        if (empty($country) && empty($service)) {
            return null;
        }

        // Initiate mapper by customer ID
        $mapper = CustomerSupplierMapper::where('customer_id', $this->id);

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
        if (!in_array($identifier, CustomerMetaEnum::VALID_META)) {
            return null;
        }

        return CustomerMeta::where('identifier', $identifier)
                ->where('customer_id', $this->id)
                ->first();
    }

    public function createMeta(string $identifier, string $value)
    {
        if (empty($identifier) || empty($value)) {
            return false;
        }

        if (!in_array($identifier, CustomerMetaEnum::VALID_META) || !is_string($value)) {
            return false;
        }

        // If meta already exists, then we update it
        $meta = CustomerMeta::where('identifier', $identifier)
                    ->where('customer_id', $this->id)
                    ->first();

        if ($meta) {
            $meta->value = $value;

            if (!$meta->update()) {
                return false;
            }
    
            return $meta;
        }

        // Otherwise if meta not exists, then we create
        $meta = new CustomerMeta([
            'customer_id' => $this->id,
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

        $newLog = new CustomerLog([
            'target_id' => $this->id,
            'description' => $message,
            'action_by_id' => $loggedInUser->id ?? null,
        ]);

        return $newLog->save();
    }

    /** ALL PRIVATE FUNCTIONS */
    public static function boot()
    {
        self::modelBoot();
    }

    private static function beforeUpdate(self $newItem)
    {
        $oldItem = self::find($newItem->id);
        $logDetails = "";

        $generalLogDetails = $newItem->getDetailsChangedForUpdateBoot($oldItem);
        if (!empty($generalLogDetails)) {
            $logDetails .= "General details have been updated as bloew:\n- ";
            $logDetails .= implode("\n- ", $generalLogDetails);
        }

        $senderDetails = $newItem->getContactDetailsChangedForUpdateBoot(unserialize($oldItem->default_sender), unserialize($newItem->default_sender));
        if (!empty($senderDetails)) {
            $logDetails .= "Sender details have been updated as below:\n- ";
            $logDetails .= implode("\n- ", $senderDetails);
        }

        $receiverDetails = $newItem->getContactDetailsChangedForUpdateBoot(unserialize($oldItem->default_receiver), unserialize($newItem->default_receiver));
        if (!empty($receiverDetails)) {
            $logDetails .= "Receiver details have been updated as below:\n- ";
            $logDetails .= implode("\n- ", $receiverDetails);
        }

        $pricingDetails = $newItem->getPriceConfigurationDetailsChangedForUpdateBoot($oldItem);
        if (!empty($pricingDetails)) {
            $logDetails .= "Pricing configuration details have been updated as below:\n";
            $logDetails .= implode("\n", $pricingDetails);
        }

        return !empty($logDetails) ? $newItem->addLog($logDetails) : true;
    }

    private static function afterCreated(self $newItem)
    {
        $logDetails = "This customer #{$newItem->id} has been created with the following details:\n- ";
        $logDetails .= implode("\n- ", $newItem->getDetailsForNewCustomerCreated());

        $logDetails .= "\n\nSender details have been set with the following details:\n- ";
        $logDetails .= implode("\n- ", $newItem->getContactDetailsForNewCustomerCreated(unserialize($newItem->default_sender)));

        $logDetails .= "\nReceiver details have been set with the following details:\n- ";
        $logDetails .= implode("\n- ", $newItem->getContactDetailsForNewCustomerCreated(unserialize($newItem->default_receiver)));

        return $newItem->addLog($logDetails);
    }

    private function getDetailsChangedForUpdateBoot(?self $oldItem)
    {
        $generalLogDetails = [];
        $generalColumns = collect(CustomerEnum::LOG_COLUMNS)->except(CustomerEnum::LOG_SEPARATE_COLUMNS);

        foreach ($generalColumns as $column => $title) {            
            if (is_null($oldItem->{$column}) || is_null($this->{$column})) {
                continue;
            }

            if ($oldItem->{$column} == $this->{$column}) {
                continue;
            }

            switch ($column) {
                case 'status':
                    $oldValue = CustomerEnum::MAP_STATUSES[$oldItem->{$column}] ?? 'Unknown';
                    $newValue = CustomerEnum::MAP_STATUSES[$this->{$column}] ?? 'Unknown';
                    break;
                case 'type':
                    $oldValue = CustomerEnum::MAP_TYPES[$oldItem->{$column}] ?? 'Unknown';
                    $newValue = CustomerEnum::MAP_TYPES[$this->{$column}] ?? 'Unknown';
                    break;
                default:
                    $oldValue = $oldItem->{$column};
                    $newValue = $this->{$column};
                    break;
            }

            $generalLogDetails[] = "{$title}: {$oldValue} -> {$newValue}";
        }

        return $generalLogDetails;
    }

    private function getContactDetailsChangedForUpdateBoot(array $oldContactDetails, array $newContactDetails)
    {        
        $contactLogDetails = [];
        foreach (CustomerEnum::DEFAULT_SENDER_RECEIVER_COLUMNS as $column => $title) {            
            if (is_null($oldContactDetails[$column]) || is_null($newContactDetails[$column])) {
                continue;
            }

            if ($oldContactDetails[$column] == $newContactDetails[$column]) {
                continue;
            }

            $contactLogDetails[] = "{$title}: {$oldContactDetails[$column]} -> {$newContactDetails[$column]}";
        }

        return $contactLogDetails;
    }

    private function getDetailsForNewCustomerCreated()
    {
        $generalLogDetails = [];
        $generalColumns = collect(CustomerEnum::LOG_COLUMNS)->except(CustomerEnum::LOG_SEPARATE_COLUMNS);

        foreach ($generalColumns as $column => $title) {
            switch ($column) {
                case 'status':
                    $value = CustomerEnum::MAP_STATUSES[$this->{$column}] ?? 'Unknown';
                    break;
                case 'type':
                    $value = CustomerEnum::MAP_TYPES[$this->{$column}] ?? 'Unknown';
                    break;
                default:
                    $value = $this->{$column};
                    break;
            }

            $generalLogDetails[] = "{$title}: {$value}";
        }

        return $generalLogDetails;
    }

    private function getPriceConfigurationDetailsForNewCustomerCreated()
    {
        $priceConfigs = unserialize($this->price_configs);
        if (empty($priceConfigs)) {
            return [];
        }

        $priceLogDetails = [];
        foreach (CustomerEnum::PRICE_COLUMNS as $pricingType => $configuration) {
            if (empty($priceConfigs[$pricingType])) {
                continue;
            }

            $priceDetailsForThisType = $priceConfigs[$pricingType];

            switch ($pricingType) {
                case CustomerEnum::PRICING_TYPE_FULFILMENT:
                    $priceLogDetails[] = '--- FULFILLMENT PRICING DETAILS BELOW:';
                    foreach ($configuration as $configType => $configDetails) {
                        if (empty($priceDetailsForThisType[$configType])) {
                            continue;
                        }

                        $priceLogForThisConfigType = collect($configDetails)
                            ->map(function($fieldTitle, $fieldKey) use ($priceDetailsForThisType, $configType) {
                                return "     - {$fieldTitle}: {$priceDetailsForThisType[$configType][$fieldKey]}";
                            })
                            ->values()
                            ->toArray();

                        $priceLogDetails = array_merge($priceLogDetails, $priceLogForThisConfigType);
                    }
                    break;
                default:
                    $priceLogDetails[] = '--- PRICING TYPE NOT VALID';
                    break;
            }
        }

        return $priceLogDetails;
    }

    private function getPriceConfigurationDetailsChangedForUpdateBoot(?self $oldItem)
    {
        $oldPriceConfigs = unserialize($oldItem->price_configs);
        // If there is no pricing set before, then we call the get details for created function instead
        if (empty($oldPriceConfigs)) {
            return $this->getPriceConfigurationDetailsForNewCustomerCreated();
        }

        $newPriceConfigs = unserialize($this->price_configs);

        if ($oldPriceConfigs == $newPriceConfigs) {
            return [];
        }
        
        $priceLogDetails = [];
        foreach (CustomerEnum::PRICE_COLUMNS as $pricingType => $configuration) {
            $oldPriceDetailsForThisType = $oldPriceConfigs[$pricingType] ?? [];
            $newPriceDetailsForThisType = $newPriceConfigs[$pricingType] ?? [];

            if ($oldPriceDetailsForThisType == $newPriceDetailsForThisType) {                
                continue;
            }

            switch ($pricingType) {
                case CustomerEnum::PRICING_TYPE_FULFILMENT:
                    $priceLogDetails[] = '--- FULFILLMENT PRICING DETAILS BELOW:';

                    foreach ($configuration as $configType => $configDetails) {
                        $oldConfigDetails = $oldPriceDetailsForThisType[$configType] ?? [];
                        $newConfigDetails = $newPriceDetailsForThisType[$configType] ?? [];

                        $priceLogForThisConfigType = collect($configDetails)
                            ->map(function($fieldTitle, $fieldKey) use ($oldConfigDetails, $newConfigDetails) {
                                $oldValue = $oldConfigDetails[$fieldKey] ?? 'Not Set';
                                $newValue = $newConfigDetails[$fieldKey] ?? 'Not Set';

                                return $oldValue != $newValue
                                    ? "     - {$fieldTitle}: {$oldValue} -> {$newValue}"
                                    : null;
                            })
                            ->filter()
                            ->values()
                            ->toArray();

                        $priceLogDetails = array_merge($priceLogDetails, $priceLogForThisConfigType);
                    }
                    break;
                default:
                    $priceLogDetails[] = '--- PRICING TYPE NOT VALID';
                    break;
            }
        }

        return $priceLogDetails;
    }

    private function getContactDetailsForNewCustomerCreated(?array $contactDetails = [])
    {
        if (empty($contactDetails)) {
            return ['Contact details have not been set'];
        }
        
        $contactLogDetails = [];
        foreach (CustomerEnum::DEFAULT_SENDER_RECEIVER_COLUMNS as $column => $title) {
            $contactLogDetails[] = "{$title}: {$contactDetails[$column]}";
        }

        return $contactLogDetails;
    }
}
