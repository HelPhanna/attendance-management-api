<?php

namespace App\Http\Controllers;

use App\Actions\Enrollment\CreateEnrollment;
use App\Actions\Enrollment\DeleteEnrollment;
use App\Actions\Enrollment\UpdateEnrollment;
use App\Models\Classes;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EnrollmentController extends Controller
{
    /**
     * List all enrollments.
     */
    public function index()
    {
        try {
            $enrollments = Enrollment::with(['student.user', 'classes'])->get();

            return response()->json([
                'list' => [
                    'data' => $enrollments,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching enrollments',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Enroll a student in a class.
     */
    public function store(Request $request)
    {
        try {
            $action     = new CreateEnrollment();
            $enrollment = $action->create($request);

            return response()->json([
                'data'    => $enrollment->load(['student.user', 'classes']),
                'message' => 'Student enrolled successfully',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error enrolling student',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show a specific enrollment.
     */
    public function show(Enrollment $enrollment)
    {
        try {
            return response()->json([
                'data' => $enrollment->load(['student.user', 'classes']),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching enrollment',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an enrollment record.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        try {
            $action            = new UpdateEnrollment();
            $updatedEnrollment = $action->update($request, $enrollment);

            return response()->json([
                'data'    => $updatedEnrollment,
                'message' => 'Enrollment updated successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating enrollment',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete (unenroll) a specific enrollment by its ID.
     */
    public function destroy(Enrollment $enrollment)
    {
        try {
            $action = new DeleteEnrollment();
            $action->delete($enrollment);

            return response()->json([
                'message' => 'Student unenrolled successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error unenrolling student',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List students enrolled in a specific class.
     */
    public function listClassStudents(Classes $class)
    {
        try {
            $students = Enrollment::query()
                ->where('class_id', $class->id)
                ->with(['student.user'])
                ->get()
                ->map(fn(Enrollment $e) => $e->student)
                ->filter()
                ->values();

            return response()->json([
                'list' => [
                    'data' => $students,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching class students',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
