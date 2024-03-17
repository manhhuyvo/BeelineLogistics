<?php

namespace App\Repositories\Staff;
use App\Models\Staff\Log as StaffLog;
use App\Enums\StaffEnum;
use App\Repositories\Base\BaseLogRepo;

class LogRepo extends BaseLogRepo
{    
    public function __construct(StaffLog $model)
    {
        $this->model = $model;
    }

    public function getFilterableColumns(): array
    {
        return StaffEnum::LOG_FILTERABLE_COLUMNS;
    }
}