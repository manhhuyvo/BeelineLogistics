<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\BaseRepoInterface;

interface BaseLogRepoInterface extends BaseRepoInterface
{
    public function findAllByTargetIdWithPagination(int $targetId = 0, array $filters = [], array $columns = ['*'], array $relations = [], array $extra = [], array $appends = [], array $paginationDetails = [50, 'page']): ?LengthAwarePaginator;
}