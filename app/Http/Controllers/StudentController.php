<?php

namespace App\Http\Controllers;

use App\Actions\Student\CreateStudent;
use App\Actions\Student\DeleteStudent;
use App\Actions\Student\UpdateStudent;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    
    public function index(Request $request)
    {
        try {
            $query = Student::query()->with('user');

            // Optional status filter
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            $students = $query->get();

            return response()->json([
                'success' => true,
                'data'    => $students,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching students',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $action  = new CreateStudent();
            $student = $action->create($request);

            return response()->json([
                'data'    => $student->load('user'),
                'message' => 'Student created successfully',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error creating student',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        try {
            $student->load('user');
            return response()->json([
                'data' => $student,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching student',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        try {
            $action         = new UpdateStudent();
            $updatedStudent = $action->update($request, $student);

            return response()->json([
                'data'    => $updatedStudent,
                'message' => 'Student updated successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error updating student',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            $action = new DeleteStudent();
            $action->delete($student);

            return response()->json([
                'message' => 'Student deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting student',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
