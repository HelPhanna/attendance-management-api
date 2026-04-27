<?php

namespace App\Actions\RolePermission;

use App\Models\RolePermission;
use Illuminate\Http\Request;

class DeleteRolePermission
{
    /**
     * Detach one or more permissions from a role.
     *
     * Expected request body:
     * {
     *   "role_id": 1,
     *   "permission_id": [2, 3]
     * }
     */
    public function execute(Request $request)
    {
        $validated = $request->validate([
            'role_id'          => ['required', 'exists:roles,id'],
            'permission_id'    => ['required', 'array', 'min:1'],
            'permission_id.*'  => ['exists:permissions,id'],
        ]);

        $deleted = RolePermission::where('role_id', $validated['role_id'])
            ->whereIn('permission_id', $validated['permission_id'])
            ->delete();

        return response()->json([
            'message'         => 'Permissions detached successfully.',
            'detached_count'  => $deleted,
        ], 200);
    }
}
