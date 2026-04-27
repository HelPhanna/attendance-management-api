<?php

namespace App\Actions\Teacher;

use App\Models\Role;
use App\Models\Teacher;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FilterTeacherFromUser
{
    public function execute(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:active,inactive',
            'teacher_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        //Get teacher role ID
        $teacherRoleId = Role::where('key', 'teacher')->value('id');

        if (!$teacherRoleId) {
            return collect([]);
        }

        //Get all user_ids with teacher role
        $teacherUserIds = UserRole::where('role_id', $teacherRoleId)
            ->pluck('user_id');

        //Auto create missing teacher rows
        foreach ($teacherUserIds as $userId) {
            Teacher::firstOrCreate(
                ['user_id' => $userId],
                [
                    'teacher_code' => 'TCH' . str_pad($userId, 4, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]
            );
        }

        //uery teachers + load user name & email
        $query = Teacher::with(['user:id,name,email'])
            ->whereIn('user_id', $teacherUserIds)
            ->select(['id', 'user_id', 'teacher_code', 'status']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('teacher_code')) {
            $query->where('teacher_code', 'like', '%' . $request->teacher_code . '%');
        }

        return $query->get();
    }
}
