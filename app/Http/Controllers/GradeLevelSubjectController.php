<?php

namespace App\Http\Controllers;

use App\Actions\GradeLevelSubject\CreateGradeLevelSubject;
use App\Actions\GradeLevelSubject\DeleteGradeLevelSubject;
use App\Actions\GradeLevelSubject\UpdateGradeLevelSubject;
use App\Models\GradeLevel;
use App\Models\GradeLevelSubject;
use Illuminate\Http\Request;

class GradeLevelSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $gradeLevels = GradeLevel::with('subjects')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $gradeLevels,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch grade level subjects.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateGradeLevelSubject $action)
    {
        try {
            $result = $action->execute($request);

            return response()->json([
                'success' => true,
                'message' => 'Subjects assigned to grade level successfully.',
                'data' => $result,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign subjects.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $record = GradeLevelSubject::find($id);

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $record,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch record.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UpdateGradeLevelSubject $action)
    {
        try {
            $result = $action->execute($request);

            return response()->json([
                'success' => true,
                'message' => 'Grade level subjects updated successfully.',
                'data' => $result,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, DeleteGradeLevelSubject $action)
    {
        try {
            $result = $action->execute($id);

            return response()->json([
                'success' => true,
                'message' => 'All subjects removed from grade level.',
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}
