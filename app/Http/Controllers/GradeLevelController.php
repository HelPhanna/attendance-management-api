<?php

namespace App\Http\Controllers;

use App\Actions\GradeLevel\CreateGradeLevel;
use App\Actions\GradeLevel\DeleteGradeLevel;
use App\Actions\GradeLevel\UpdateGradeLevel;
use App\Models\GradeLevel;
use Illuminate\Http\Request;

class GradeLevelController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $gradeLevels = GradeLevel::all();

            return response()->json([
                'success' => true,
                'data' => $gradeLevels,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch grade levels.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateGradeLevel $createGradeLevel)
    {
        try {
            $gradeLevel = $createGradeLevel->execute($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Grade level created successfully.',
                'data' => $gradeLevel,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create grade level.',
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
            $gradeLevel = GradeLevel::find($id);

            if (!$gradeLevel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grade level not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $gradeLevel,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch grade level.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id, UpdateGradeLevel $updateGradeLevel)
    {
        try {
            $gradeLevel = $updateGradeLevel->execute($id, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Grade level updated successfully.',
                'data' => $gradeLevel,
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
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, DeleteGradeLevel $deleteGradeLevel)
    {
        try {
            $deleteGradeLevel->execute($id);

            return response()->json([
                'success' => true,
                'message' => 'Grade level deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}
