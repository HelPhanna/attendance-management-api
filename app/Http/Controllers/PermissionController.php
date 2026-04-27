<?php

namespace App\Http\Controllers;

use App\Actions\Permission\CreatePermission;
use App\Actions\Permission\DeletePermission;
use App\Actions\Permission\UpdatePermission;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permission = Permission::all();
        return response()->json([
            'list' => [
                'data' => $permission,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $createPermission = new CreatePermission();
            $permission = $createPermission->create($request);

            return response()->json([
                'data' => $permission,
                'message' => 'Permission created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating permission',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::findOrFail($id);
        if ($permission) {
            return response()->json([
                'success' => true,
                'data' => $permission,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found!',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        try {
            $action = new UpdatePermission();
            $updatedPermission = $action->update($request, $permission);

            return response()->json([
                'data' => $updatedPermission,
                'message' => 'Permission updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating permission',
                'error' => $e->getMessage()
            ], 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            $action = new DeletePermission();
            $action->delete($permission);

            return response()->json([
                'message' => 'Permission deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting permission',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
