<?php

namespace App\Repositories\Staff;
use App\Models\Staff;
use App\Models\User;
use App\Models\Staff\Log as StaffLog;
use Carbon\Carbon;
use App\Enums\StaffEnum;

class LogRepo
{
    public function getAll()
    {
        return StaffLog::all();
    }

    public function getAllWithRelations()
    {
        return StaffLog::with(['target', 'action_user', 'action_user.owner'])->get();
    }

    public function getAllWithPagination(?array $data = [], ?int $perPage = 50, ?array $columns = ['*'], ?string $pageName = 'page')
    {
        $allRecords = StaffLog::with(['target', 'action_user', 'action_user.staff']);        
        if (!empty($data)){
            foreach($data as $key => $value) {
                if (empty($value) || !in_array($key, StaffEnum::LOG_FILTERABLE_COLUMNS)) {
                    continue;
                }
                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $allRecords = $allRecords->where($key, 'like', "%$value%");
            }

            // Created between date range
            if (!empty($data['date_from']) && !empty($data['date_to'])) {
                $allRecords = $data['date_from'] == $data['date_to']
                                ? $allRecords->whereDate('created_at', $data['date_from'])
                                : $allRecords->whereBetween('created_at', [
                                    Carbon::parse($data['date_from'])->startOfDay()->format('Y-m-d H:i:S'),
                                    Carbon::parse($data['date_to'])->endOfDay()->format('Y-m-d H:i:S')
                                ]);
            }
        }

        return $allRecords->paginate($perPage, $columns, $pageName);
    }
}