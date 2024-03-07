<?php

namespace App\Repositories\Staff;
use App\Models\Staff\Log as StaffLog;
use App\Repositories\BaseRepo;
use App\Enums\StaffEnum;

class LogRepo extends BaseRepo
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