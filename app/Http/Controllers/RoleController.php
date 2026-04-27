<?php

namespace App\Http\Controllers;

use App\Actions\Role\CreateRole;
use App\Actions\Role\DeleteRole;
use App\Actions\Role\UpdateRole;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Role::with('permissions')->get();
        return response()->json([
            'list' => [
                'data' => $role
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $action = new CreateRole();
            $role = $action->create($request);

            return response()->json([
                'data' => $role,
                'message' => 'Role created successfully',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating role',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        if ($role) {
            return response()->json([
                'data' => $role
            ], 200);
        } else {
            return response()->json([
                'message' => 'Role not found!'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $action = new UpdateRole();
            $updatedRole = $action->update($request, $role);

            return response()->json([
                'data' => $updatedRole,
                'message' => 'Role updated successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating role',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $action = new DeleteRole();
            $action->delete($role);

            return response()->json([
                'data' => $role,
                'message' => 'Role deleted successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Cannot delete role',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting role',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
