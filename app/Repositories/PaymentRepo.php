<?php

namespace App\Repositories;

use App\Repositories\Base\BaseRepo;
use App\Models\Payment;
use App\Enums\PaymentEnum;

class PaymentRepo extends BaseRepo
{    
    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    public function getFilterableColumns(): array
    {
        return PaymentEnum::FILTERABLE_COLUMNS;
    }
}