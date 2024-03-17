<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\BaseRepoInterface;
use Illuminate\Database\Eloquent\Model;

interface BasePayableEntityInterface extends BaseRepoInterface
{
    public function updateStatus(?int $id, ?string $status): bool;

    public function updatePaymentStatus(?int $id, ?string $status): bool;

    public function isOutstanding(?int $id): bool;
}