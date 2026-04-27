<?php

namespace App\Actions\UserRole;

use App\Models\UserRole;
use Illuminate\Http\Request;

class CreateUserRole
{
    public function execute(Request $request): array
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role_id' => ['required', 'array'],
            'role_id.*' => ['exists:roles,id'],
        ]);

        $userId = $validated['user_id'];

        $created = [];
        $skipped = [];
        $successCount = 0;

        foreach ($validated['role_id'] as $rid) {
            $exists = UserRole::where('user_id', $userId)
                ->where('role_id', $rid)
                ->exists();

            if ($exists) {
                $skipped[] = $rid;
                continue;
            }

            $created[] = UserRole::create([
                'user_id' => $userId,
                'role_id' => $rid,
            ]);

            $successCount++;
        }

        return [
            'user_id' => $userId,
            'roles' => $created,
            'assigned_count' => $successCount,
            'skipped_roles' => $skipped,
        ];
    }
}
