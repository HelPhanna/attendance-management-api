<?php

namespace App\Actions\UserRole;

use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateUserRole
{
    public function execute(Request $request): array
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role_id' => ['required', 'array'],
            'role_id.*' => ['exists:roles,id'],
        ]);

        return DB::transaction(function () use ($validated) {

            $userId = $validated['user_id'];
            $newRoles = $validated['role_id'];

            // Get current roles
            $currentRoles = UserRole::where('user_id', $userId)
                ->pluck('role_id')
                ->toArray();

            // Determine roles to add
            $rolesToAdd = array_diff($newRoles, $currentRoles);

            // Determine roles to remove
            $rolesToRemove = array_diff($currentRoles, $newRoles);

            // Delete removed roles
            UserRole::where('user_id', $userId)
                ->whereIn('role_id', $rolesToRemove)
                ->delete();

            $added = [];
            foreach ($rolesToAdd as $rid) {
                $added[] = UserRole::create([
                    'user_id' => $userId,
                    'role_id' => $rid,
                ]);
            }

            return [
                'user_id' => $userId,
                'added_roles' => $added,
                'removed_roles' => array_values($rolesToRemove),
                'final_roles' => $newRoles,
            ];
        });
    }
}
