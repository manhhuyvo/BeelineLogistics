<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\BaseRepoInterface;
use Carbon\Carbon;

class BaseRepo implements BaseRepoInterface
{
    protected Model $model;

    public function findById(?int $id, array $columns = ['*'], array $relations = []): ?Model
    {
        if (empty($id)) {
            return null;
        }

        return $this->model->select($columns)->with($relations)->find($id);
    }

    public function findAll(array $columns = ['*'], array $relations = [], array $extra = []): Collection
    {
        $results = $this->model->with($relations);
        if (!empty($extra)) {
            /**
             * @var array $value
             */
            foreach ($extra as $query => $value) {
                $results = $results->{$query}(...$value);
            }
        }

        return $results->get($columns);
    }

    public function findAllWithPagination(
        array $filters = [],
        array $columns = ['*'],
        array $relations = [],
        array $extra = [],
        array $appends = [],
        array $paginationDetails = [50, 'page']
    ): ?LengthAwarePaginator
    {
        [$perPage, $pageName] = $paginationDetails;
        $results = $this->model->with($relations);
        $filterableColumns = $this->getFilterableColumns();

        if (!empty($filters)){
            foreach($filters as $key => $value) {
                if (empty($value) || !in_array($key, $filterableColumns)) {
                    continue;
                }
                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $results = $results->where($key, 'like', "%$value%")->orderBy;
            }

            // Created between date range
            if (!empty($data['date_from']) && !empty($data['date_to'])) {
                $results = $data['date_from'] == $data['date_to']
                                ? $results->whereDate('created_at', $data['date_from'])
                                : $results->whereBetween('created_at', [
                                    Carbon::parse($data['date_from'])->startOfDay()->format('Y-m-d H:i:S'),
                                    Carbon::parse($data['date_to'])->endOfDay()->format('Y-m-d H:i:S')
                                ]);
            }
        }

        $results = $this->model->with($relations);
        if (!empty($extra)) {
            /**
             * @var array $value
             */
            foreach ($extra as $query => $value) {
                $results->{$query}(...$value);
            }
        }

        return $results->paginate($perPage, $columns, $pageName);
    }

    public function create(array $payload): ?Model
    {
        $model = $this->model->create($payload);

        return $model->fresh();
    }

    public function update(?int $id, array $payload): bool
    {
        $model = $this->findById($id);

        return $model->update($payload);
    }

    public function delete(?int $id): bool
    {
        $model = $this->findById($id);

        return $model->delete();
    }

    public function getFilterableColumns(): array
    {
        return [];
    }
}