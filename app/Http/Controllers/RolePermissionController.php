<?php

namespace App\Http\Controllers;

use App\Actions\RolePermission\CreateRolePermission;
use App\Actions\RolePermission\UpdateRolePermissions;
use App\Actions\RolePermission\DeleteRolePermission;
use App\Models\Role;
use App\Models\RolePermission;
// use Illuminate\Http\Request;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class RolePermissionController extends Controller
{
    public function index()
    {
        $rolePermission = RolePermission::all();
        return response()->json([
            'list' => [
                'data' => $rolePermission
            ]
        ]);
    }
    // CREATE (attach permission(s))
    public function store(Request $request, Role $role, CreateRolePermission $action)
    {
        try {
            // Make sure role_id is included for the action validation
            // $request->merge(['role_id' => $role->id]);

            return $action->execute($request);
        } catch (ValidationException $e) {
            return response()->json([
                'data' => null,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'data' => null,
                'message' => 'Server error.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // UPDATE (sync/replace or your current "add only" logic)
    public function update(Request $request, Role $role, UpdateRolePermissions $action)
    {
        try {

            return $action->execute($request);
        } catch (ValidationException $e) {
            return response()->json([
                'data' => null,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'data' => null,
                'message' => 'Server error.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // DELETE (detach permission(s))
    public function destroy(Request $request, DeleteRolePermission $action)
    {
        try {
            return $action->execute($request);
        } catch (ValidationException $e) {
            return response()->json([
                'data'    => null,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'data'    => null,
                'message' => 'Server error.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
