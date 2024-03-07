<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepoInterface
{
    public function findAll(array $columns = ['*'], array $relations = [], array $extra = []): Collection;

    public function findById(?int $id, array $columns = ['*'], array $relations = []): ?Model;

    public function findAllWithPagination(array $filters = [], array $columns = ['*'], array $relations = [], array $extra = [], array $appends = [], array $paginationDetails = [50, 'page']): ?LengthAwarePaginator;

    public function create(array $payload): ?Model;
    
    public function update(?int $id, array $payload): bool;
    
    public function delete(?int $id): bool;
}