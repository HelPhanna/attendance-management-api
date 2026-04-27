<?php

namespace App\Http\Controllers;

use App\Actions\Teacher\FilterTeacherFromUser;
use App\Actions\Teacher\UpdateTeacher;
use App\Models\Teacher;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, FilterTeacherFromUser $action)
    {
        try {
            $data = $action->execute($request);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $teacher = Teacher::findOrFail($id);

            return response()->json([
                'message' => 'Teacher retrieved successfully',
                'data' => $teacher
            ], 200);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Teacher not found'
            ], 404);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            $teacher = (new UpdateTeacher())->execute($request, $id);

            return response()->json([
                'message' => 'Teacher updated successfully',
                'data' => $teacher
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
