<?php

namespace App\Actions\UserRole;

use App\Models\UserRole;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeleteUserRole
{
    public function execute(Request $request): array
    {
        try {
            $validated = $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'role_id' => ['required', 'array'],
                'role_id.*' => ['exists:roles,id'],
            ]);

            return DB::transaction(function () use ($validated) {

                $userId = $validated['user_id'];
                $roleIds = $validated['role_id'];

                // Delete selected roles
                UserRole::where('user_id', $userId)
                    ->whereIn('role_id', $roleIds)
                    ->delete();

                // Check remaining roles
                $remainingRoles = UserRole::where('user_id', $userId)->count();

                $defaultAssigned = false;

                if ($remainingRoles === 0) {

                    // Get student role
                    $studentRole = Role::where('name', 'student')->first();

                    if ($studentRole) {
                        UserRole::create([
                            'user_id' => $userId,
                            'role_id' => $studentRole->id,
                        ]);

                        $defaultAssigned = true;
                    }
                }

                return [
                    'user_id' => $userId,
                    'deleted_roles' => $roleIds,
                    'default_student_assigned' => $defaultAssigned,
                ];
            });
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete roles: ' . $e->getMessage());
        }
    }
}
