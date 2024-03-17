<?php

namespace App\Repositories\User;
use App\Models\User\Log as UserLog;
use App\Enums\UserEnum;
use App\Repositories\Base\BaseLogRepo;

class LogRepo extends BaseLogRepo
{    
    public function __construct(UserLog $model)
    {
        $this->model = $model;
    }

    public function getFilterableColumns(): array
    {
        return UserEnum::LOG_FILTERABLE_COLUMNS;
    }
}