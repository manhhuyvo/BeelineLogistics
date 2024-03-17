<?php

namespace App\Repositories\Supplier;
use App\Models\Supplier\Log as SupplierLog;
use App\Enums\SupplierEnum;
use App\Repositories\Base\BaseLogRepo;

class LogRepo extends BaseLogRepo
{    
    public function __construct(SupplierLog $model)
    {
        $this->model = $model;
    }

    public function getFilterableColumns(): array
    {
        return SupplierEnum::LOG_FILTERABLE_COLUMNS;
    }
}