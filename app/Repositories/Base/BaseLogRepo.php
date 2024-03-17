<?php

namespace App\Repositories\Base;

use App\Repositories\Contracts\BaseLogRepoInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Base\BaseRepo;
use Carbon\Carbon;

class BaseLogRepo extends BaseRepo implements BaseLogRepoInterface
{
    protected Model $model;

    public function findAllByTargetIdWithPagination(
        int $targetId = 0,
        array $filters = [],
        array $columns = ['*'],
        array $relations = [],
        array $extra = [],
        array $appends = [],
        array $paginationDetails = [50, 'page']
    ): ?LengthAwarePaginator
    {
        [$perPage, $pageName] = $paginationDetails;
        $results = $this->model->with($relations)->where('target_id', $targetId);
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
                $results = $results->where($key, 'like', "%$value%");
            }

            // Created between date range
            if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
                $results = $filters['date_from'] == $filters['date_to']
                                ? $results->whereDate('created_at', $filters['date_from'])
                                : $results->whereBetween('created_at', [
                                    Carbon::parse($filters['date_from'])->startOfDay()->format('Y-m-d H:i:S'),
                                    Carbon::parse($filters['date_to'])->endOfDay()->format('Y-m-d H:i:S')
                                ]);
            }
        }

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
}