<?php

namespace App\Actions\RolePermission;

use App\Models\RolePermission;
use Illuminate\Http\Request;

class CreateRolePermission
{
    public function execute(Request $request)
    {
        $validated = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'permission_id' => ['required', 'array'],
            'permission_id.*' => ['exists:permissions,id'],
        ]);

        $roleId = $validated['role_id'];
        $permissionIds = $validated['permission_id'];

        RolePermission::where('role_id', $roleId)->delete();

        $created = [];

        foreach ($permissionIds as $pid) {
            $created[] = RolePermission::create([
                'role_id' => $roleId,
                'permission_id' => $pid,
            ]);
        }

        return response()->json([
            'data' => [
                'role_id' => $roleId,
                'created_count' => count($created),
                'created' => $created,
            ],
            'message' => 'Permissions assigned to role successfully.',
        ], 201);
    }
}
