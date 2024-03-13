<?php

namespace App\Repositories;

use App\Repositories\Base\BaseRepo;
use App\Models\Transaction;
use App\Enums\TransactionEnum;

class TransactionRepo extends BaseRepo
{    
    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function getFilterableColumns(): array
    {
        return TransactionEnum::FILTERABLE_COLUMNS;
    }
}