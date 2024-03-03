<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\User;
use App\Models\SalaryPayCheck;
use App\Models\Order;
use App\Models\Fulfillment\ProductPayment as FulfillmentProductPayment;
use App\Enums\StaffPositionEnum;
use App\Enums\StaffEnum;
use App\Models\Staff\Log as StaffLog;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staffs';

    protected $fillable = [
        'full_name',
        'phone',
        'address',
        'dob',
        'type',
        'salary_configs',
        'position',
        'status',
        'note',
    ];

    protected $casts = [
        'salary_configs' => 'array',
        'dob' => 'date:d/m/Y',
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    /** Staff Status */
    const STATUS_CURRENT = 1;
    const STATUS_TEMPORARY_OFF = 2;
    const STATUS_OFF = 3;

    const STAFF_STATUSES = [
        self::STATUS_CURRENT,
        self::STATUS_TEMPORARY_OFF,
        self::STATUS_OFF,
    ];

    const MAP_STATUSES_COLOR = [
        self::STATUS_CURRENT => 'green',
        self::STATUS_TEMPORARY_OFF => 'orange',
        self::STATUS_OFF => 'red',
    ];

    const MAP_STATUSES = [
        self::STATUS_CURRENT => 'Current',
        self::STATUS_TEMPORARY_OFF => 'Temporary Off',
        self::STATUS_OFF => 'Off',
    ];

    /** Staff Positions */
    const POSITION_DIRECTOR = 1;
    const POSITION_ACCOUNTANT = 2;
    const POSITION_SALES = 3;
    const POSITION_CUSTOMER_SERVICE = 4;
    const POSITION_IT = 5;

    const STAFF_POSITIONS = [
        self::POSITION_DIRECTOR,
        self::POSITION_ACCOUNTANT,
        self::POSITION_SALES,
        self::POSITION_CUSTOMER_SERVICE,
        self::POSITION_IT,
    ];

    const MAP_POSITIONS = [
        self::POSITION_DIRECTOR => 'Executive',
        self::POSITION_ACCOUNTANT => 'Accountant',
        self::POSITION_SALES => 'Sales',
        self::POSITION_CUSTOMER_SERVICE => 'Customer Service',
        self::POSITION_IT => 'IT',
    ];

    /** Employment Types */
    const TYPE_FULLTIME = 1;
    const TYPE_PARTIME = 2;
    const TYPE_CASUAL = 3;
    const TYPE_INTERN = 4;

    const MAP_TYPES = [
        self::TYPE_FULLTIME => "Full Time",
        self::TYPE_PARTIME => "Part Time",
        self::TYPE_CASUAL => "Casual",
        self::TYPE_INTERN => "Intern",
    ];

    const STAFF_TYPES = [
        self::TYPE_FULLTIME,
        self::TYPE_PARTIME,
        self::TYPE_CASUAL,
        self::TYPE_INTERN,
    ];

    /** Commission Units */
    const COMMISSION_UNIT_PERCENTAGE = 1;
    const COMMISSION_UNIT_VND = 2;
    const COMMISSION_UNIT_AUD = 3;
    const COMMISSION_UNIT_USD = 4;

    const MAP_COMMISSION_UNITS = [
        self::COMMISSION_UNIT_PERCENTAGE => '%',
        self::COMMISSION_UNIT_VND => 'VND',   
        self::COMMISSION_UNIT_AUD => 'AUD',       
        self::COMMISSION_UNIT_USD => 'USD',
    ];

    const COMMISSION_UNITS = [
        self::COMMISSION_UNIT_PERCENTAGE,
        self::COMMISSION_UNIT_VND,
        self::COMMISSION_UNIT_AUD,
        self::COMMISSION_UNIT_USD,
    ];

    /* Commission Types */
    const COMMISSION_TYPE_SALE = 1;
    const COMMISSION_TYPE_ORDER = 2;

    CONST MAP_COMMISSION_TYPES = [
        self::COMMISSION_TYPE_SALE => "Every Sale",
        self::COMMISSION_TYPE_ORDER => "Every Order",
    ];

    const COMMISSION_TYPES = [
        self::COMMISSION_TYPE_SALE,
        self::COMMISSION_TYPE_ORDER,
    ];

    public static function boot()
    {
        parent::boot();

        static::updating(function ($newItem) {
            static::beforeUpdate($newItem);
        });

        static::created(function ($newItem) {
            static::afterCreated($newItem);
        });
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'staff_id', 'id');
    }

    public function account(): HasOne
    {
        return $this->hasOne(User::class, 'staff_id', 'id');
    }

    public function paychecks(): HasMany
    {
        return $this->hasMany(SalaryPayCheck::class, 'staff_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'staff_id', 'id');
    }

    public function fulfillmentProductPaymentsAction(): HasMany
    {
        return $this->hasMany(FulfillmentProductPayment::class, 'staff_id', 'id');
    }

    public function isAdmin()
    {
        return in_array($this->position, [self::POSITION_DIRECTOR, self::POSITION_ACCOUNTANT]);
    }

    public function addLog(?string $message): bool
    {
        $loggedInUser = Auth::user();

        $newLog = new StaffLog([
            'target_id' => $this->id,
            'description' => $message,
            'action_by_id' => $loggedInUser->id ?? null,
        ]);

        return $newLog->save();
    }

    /** ALL PRIVATE FUNCTIONS */
    private static function beforeUpdate(self $newItem)
    {
        $oldItem = self::find($newItem->id);
        $logDetails = $newItem->getDetailsChangedForUpdateBoot($oldItem);
        if (empty($logDetails)) {
            return ;
        }
        
        return $newItem->addLog("Staff's information has been updated as below:\n- " . implode("\n- ", $logDetails));
    }

    private static function afterCreated(self $newItem)
    {
        $logDetails = $newItem->getDetailsForNewStaffCreated();

        return $newItem->addLog(implode("\n- ", $logDetails));
    }

    private function getDetailsChangedForUpdateBoot(?self $oldItem)
    {
        $logDetails = [];
        $salaryChanged = false;

        foreach (StaffEnum::LOG_COLUMNS as $column => $title) {
            if (is_null($oldItem->{$column}) || is_null($this->{$column})) {
                continue;
            }

            if ($oldItem->{$column} == $this->{$column}) {
                continue;
            }

            if ($column == 'salary_configs') {
                $salaryChanged = true;
                continue;
            }

            switch ($column) {
                case 'status':
                    $oldValue = StaffEnum::MAP_STATUSES[$oldItem->{$column}] ?? 'Unknown';
                    $newValue = StaffEnum::MAP_STATUSES[$this->{$column}] ?? 'Unknown';
                    break;
                case 'position':
                    $oldValue = StaffEnum::MAP_POSITIONS[$oldItem->{$column}] ?? 'Unknown';
                    $newValue = StaffEnum::MAP_POSITIONS[$this->{$column}] ?? 'Unknown';
                    break;
                case 'type':
                    $oldValue = StaffEnum::MAP_TYPES[$oldItem->{$column}] ?? 'Unknown';
                    $newValue = StaffEnum::MAP_TYPES[$this->{$column}] ?? 'Unknown';
                    break;
                default:
                    $oldValue = $oldItem->{$column};
                    $newValue = $this->{$column};
                    break;
            }

            $messageField = "{$title}: {$oldValue} -> {$newValue}";
            $logDetails[] = $messageField;
        }

        if ($salaryChanged) {
            $salaryDetails = $this->getSalaryConfigsChangedForUpdateBoot($oldItem);
            $logDetails[] = "Staff's salary configurations have been updated as below:\n\t- " . implode("\n\t- ", $salaryDetails);
        }

        return $logDetails;
    }
    
    private function getSalaryConfigsChangedForUpdateBoot(?self $oldItem)
    {
        $oldSalary = unserialize($oldItem->salary_configs);
        $newSalary = unserialize($this->salary_configs);
        $salaryDetails = [];

        foreach (StaffEnum::SALARY_COLUMNS as $column => $description) {
            // For base salary field
            if ($column == 'base_salary') {
                if ($oldSalary[$column] == $newSalary[$column]) {
                    continue;
                }

                $messageField = "{$description}: {$oldSalary[$column]} -> {$newSalary[$column]}";
                $salaryDetails[] = $messageField;
                continue;
            }

            // If both old and new figures don't have commisison applied, then skip it
            if (empty($oldSalary['commission']) && empty($newSalary['commission'])) {
                continue;
            }

            // Loop through each sub fields in salary configs for the changes
            foreach ($description as $subField => $subDescription) {
                $oldSubData = !empty($oldSalary[$column]) ? $oldSalary[$column][$subField] : 'Not set';
                $newSubData = !empty($newSalary[$column]) ? $newSalary[$column][$subField] : 'Not set';

                if ($oldSubData == $newSubData) {
                    continue;
                }

                switch ($subField) {
                    case 'commission_unit':
                        $oldSubData = !empty($oldSalary[$column]) ? (StaffEnum::MAP_COMMISSION_UNITS[$oldSalary[$column][$subField]] ?? 'Unknown') : 'Not set';
                        $newSubData = !empty($newSalary[$column]) ? (StaffEnum::MAP_COMMISSION_UNITS[$newSalary[$column][$subField]] ?? 'Unknown') : 'Not set';
                        break;
                    case 'commission_type':
                        $oldSubData = !empty($oldSalary[$column]) ? (StaffEnum::MAP_COMMISSION_TYPES[$oldSalary[$column][$subField]] ?? 'Unknown') : 'Not set';
                        $newSubData = !empty($newSalary[$column]) ? (StaffEnum::MAP_COMMISSION_TYPES[$newSalary[$column][$subField]] ?? 'Unknown') : 'Not set';
                        break;
                    default:
                        $oldSubData = !empty($oldSalary[$column]) ? $oldSalary[$column][$subField] : 'Not set';
                        $newSubData = !empty($newSalary[$column]) ? $newSalary[$column][$subField] : 'Not set';
                        break;
                }

                $salaryDetails[] = "{$subDescription}: {$oldSubData} -> {$newSubData}";
            }
        }

        return $salaryDetails;
    }

    private function getDetailsForNewStaffCreated()
    {
        $logDetails = ["The staff #{$this->id} has been created with the following details:"];
        foreach (StaffEnum::LOG_COLUMNS as $column => $title) {
            if ($column == 'salary_configs') {
                continue;
            }            

            switch ($column) {
                case 'status':
                    $value = StaffEnum::MAP_STATUSES[$this->{$column}] ?? 'Unknown';
                    break;
                case 'position':
                    $value = StaffEnum::MAP_POSITIONS[$this->{$column}] ?? 'Unknown';
                    break;
                case 'type':
                    $value = StaffEnum::MAP_TYPES[$this->{$column}] ?? 'Unknown';
                    break;
                default:
                    $value = $this->{$column};
                    break;
            }

            $logDetails[] = "{$title}: {$value}";
        }

        $salaryConfigs = unserialize($this->salary_configs);
        foreach ($salaryConfigs as $column => $description) {
            if ($column == 'base_salary') {
                $logDetails[] = "{$description}: {$salaryConfigs[$column]}";
                continue;
            }

            if (empty($salaryConfigs[$column])) {
                $logDetails[] = "No commission applied";
                continue;
            }

            foreach ($description as $subField => $subDescription) {
                switch ($subField) {
                    case 'commission_unit':
                        $subData = !empty($salaryConfigs[$column]) ? (StaffEnum::MAP_COMMISSION_UNITS[$salaryConfigs[$column][$subField]] ?? 'Unknown') : 'Not set';
                        break;
                    case 'commission_type':
                        $subData = !empty($salaryConfigs[$column]) ? (StaffEnum::MAP_COMMISSION_TYPES[$salaryConfigs[$column][$subField]] ?? 'Unknown') : 'Not set';
                        break;
                    default:
                        $subData = !empty($salaryConfigs[$column]) ? $salaryConfigs[$column][$subField] : 'Not set';
                        break;
                }

                $salaryDetails[] = "{$subDescription}: {$subData}";
            }
        }

        return $logDetails;
    }
}
