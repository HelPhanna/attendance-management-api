<?php

namespace App\Http\Controllers;

use App\Actions\ClassTeacher\CreateClassTeacher;
use App\Actions\ClassTeacher\DeleteClassTeacher;
use App\Actions\ClassTeacher\UpdateClassTeacher;
use App\Models\ClassTeacher;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClassTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classTeachers = ClassTeacher::all();

        return response()->json([
            'list' => [
                'data' => $classTeachers,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $action = new CreateClassTeacher();
            $classTeacher = $action->create($request);

            return response()->json([
                'data' => $classTeacher,
                'message' => 'Class teacher assigned successfully',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error assigning class teacher',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $classTeacher = ClassTeacher::findOrFail($id);

        return response()->json([
            'data' => $classTeacher,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassTeacher $classTeacher)
    {
        try {
            $action = new UpdateClassTeacher();
            $updatedClassTeacher = $action->update($request, $classTeacher);

            return response()->json([
                'data' => $updatedClassTeacher,
                'message' => 'Class teacher updated successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating class teacher',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassTeacher $classTeacher)
    {
        try {
            $action = new DeleteClassTeacher();
            $action->delete($classTeacher);

            return response()->json([
                'data' => $classTeacher,
                'message' => 'Class teacher deleted successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Cannot delete class teacher',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting class teacher',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
