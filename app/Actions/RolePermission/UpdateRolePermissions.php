<?php

namespace App\Actions\RolePermission;

use App\Models\RolePermission;
use Illuminate\Http\Request;

class UpdateRolePermissions
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

        // Remove old permissions
        RolePermission::where('role_id', $roleId)->delete();

        // Insert new permissions
        $data = [];

        foreach ($permissionIds as $pid) {
            $data[] = [
                'role_id' => $roleId,
                'permission_id' => $pid,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        RolePermission::insert($data);

        return response()->json([
            'data' => [
                'role_id' => $roleId,
                'permissions' => $permissionIds,
            ],
            'message' => 'Role permissions updated successfully.',
        ], 200);
    }
}
