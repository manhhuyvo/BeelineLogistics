<?php

namespace App\Repositories\Base;

use App\Repositories\Contracts\BasePayableEntityInterface;

class  BasePayableEntityRepo extends BaseRepo implements BasePayableEntityInterface
{
    public function updateStatus(?int $id, ?string $status): bool
    {
        $model = $this->findById($id);

        return $model->update(['status' => $status]);
    }

    public function updatePaymentStatus(?int $id, ?string $status): bool
    {
        $model = $this->findById($id);

        return $model->update(['payment_status' => $status]);
    }

    public function isOutstanding(?int $id): bool
    {
        $model = $this->findById($id);

        return $model->outstanding_amount > 0;
    }
}