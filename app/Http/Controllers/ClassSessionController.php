<?php

namespace App\Http\Controllers;

use App\Actions\ClassSession\CreateClassSession;
use App\Actions\ClassSession\DeleteClassSession;
use App\Actions\ClassSession\UpdateClassSession;
use App\Models\ClassSession;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClassSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classSession = ClassSession::all();

        return response()->json([
            'list' => [
                'data' => $classSession
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateClassSession $createClassSession)
    {
        try {
            $classSession = $createClassSession->create($request);
            return response()->json([
                'dats' => $classSession,
                'message' => 'Class session created successfully'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating class session',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $classSesion = ClassSession::findOrFail($id);

        return response()->json([
            'data' => $classSesion,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        ClassSession $classSession,              // ✅ correct variable name
        UpdateClassSession $updateClassSession
    ) {
        try {
            $updatedData = $updateClassSession->update($request, $classSession); // ✅ pass correct variable

            return response()->json([
                'data' => $updatedData,
                'message' => 'Class session update successfully'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error updating class session',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSession $classSesion, DeleteClassSession $singleDelete)
    {
        $session = $singleDelete->delete($classSesion);

        return response()->json([
            'data' => $session,
            'message' => 'Class session deleted successfully'
        ]);
    }

    public function destroyMulti(Request $request, DeleteClassSession $action)
    {

        $request->validate([
            "ids" => "requred|array",
            "ids.*" => "integer|exists:class_sessions,id",
        ]);

        $ids = $request->input('ids');

        $data = $action->multiDelete($ids);

        return response()->json([
            'data' => $data,
            'deleted_count' => $data->count(),
            'message' => 'Class session deleted successfully'
        ]);
    }

    public function destroyAll(DeleteClassSession $deleteAction)
    {
        try {
            $deletedRecords = $deleteAction->deleteAll();

            return response()->json([
                'data' => $deletedRecords,
                'message' => 'All class session deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting class session',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
