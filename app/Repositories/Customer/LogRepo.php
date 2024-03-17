<?php

namespace App\Repositories\Customer;
use App\Models\Customer\Log as CustomerLog;
use App\Enums\CustomerEnum;
use App\Repositories\Base\BaseLogRepo;

class LogRepo extends BaseLogRepo
{    
    public function __construct(CustomerLog $model)
    {
        $this->model = $model;
    }

    public function getFilterableColumns(): array
    {
        return CustomerEnum::LOG_FILTERABLE_COLUMNS;
    }
}