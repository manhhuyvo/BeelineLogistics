<?php

namespace App\Repositories;

use App\Repositories\Base\BaseRepo;
use App\Models\Invoice;
use App\Enums\InvoiceEnum;

class PaymentRepo extends BaseRepo
{    
    public function __construct(Invoice $model)
    {
        $this->model = $model;
    }

    public function getFilterableColumns(): array
    {
        return InvoiceEnum::FILTERABLE_COLUMNS;
    }
}