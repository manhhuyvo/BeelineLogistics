<?php

namespace App\Repositories\Staff;
use App\Models\Staff;
use App\Models\User;
use App\Models\Staff\Log as StaffLog;

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
                if (empty($value) || $key == 'page' || $key == '_method') {
                    continue;
                }
                // Escape to prevent break
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($value);

                // Add conditions
                $allRecords = $allRecords->where($key, 'like', "%$value%");
            }
        }

        return $allRecords->paginate($perPage, $columns, $pageName);
    }
}